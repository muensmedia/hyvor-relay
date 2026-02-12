<?php

/*
 * Derived from symfony/sendinblue-mailer (MIT).
 * See THIRD_PARTY_NOTICES.md for the applicable copyright and license text.
 */

namespace Muensmedia\HyvorRelay\Transport;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\Envelope;
use Symfony\Component\Mailer\Exception\HttpTransportException;
use Symfony\Component\Mailer\Header\TagHeader;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractApiTransport;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Header\Headers;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class HyvorRelayApiTransport extends AbstractApiTransport
{
    private string $key;

    public function __construct(string $key, ?HttpClientInterface $client = null, ?EventDispatcherInterface $dispatcher = null, ?LoggerInterface $logger = null)
    {
        $this->key = $key;
        parent::__construct($client, $dispatcher, $logger);
    }

    public function __toString(): string
    {
        return sprintf('hyvor+api://%s', $this->getEndpoint());
    }

    private function getEndpoint(): ?string
    {
        return ($this->host ?: config('hyvor-relay.endpoint')).($this->port ? ':'.$this->port : '');
    }

    protected function doSendApi(SentMessage $sentMessage, Email $email, Envelope $envelope): ResponseInterface
    {
        $headers = [
            'Authorization' => 'Bearer '.$this->key,
        ];

        $idempotencyKey = $this->getIdempotencyKey($email);
        if ($idempotencyKey !== null) {
            $headers['X-Idempotency-Key'] = $idempotencyKey;
        }

        $response = $this->client->request('POST', $this->getEndpoint().'/api/console/sends', [
            'json' => $this->getPayload($email, $envelope),
            'headers' => $headers,
        ]);

        try {
            $statusCode = $response->getStatusCode();
            $result = $response->toArray(false);
        } catch (DecodingExceptionInterface) {
            throw new HttpTransportException('Unable to send an email: '.$response->getContent(false).sprintf(' (code %d).', $statusCode), $response);
        } catch (TransportExceptionInterface $e) {
            throw new HttpTransportException('Could not reach the remote Hyvor Relay server.', $response, 0, $e);
        }

        // Hyvor Relay responds with 200 on success (some APIs use 201). Treat any 2xx as success.
        $ok = $statusCode >= 200 && $statusCode < 300;
        $errorMessage = Arr::get($result, 'message')
            ?? Arr::get($result, 'error')
            ?? Arr::get($result, 'errors')
            ?? '';
        if ($errorMessage === '') {
            // Avoid throwing "Unable to send an email:  (code ...)" on error responses without a message.
            $errorMessage = trim((string) $response->getContent(false));
        }

        throw_if(
            ! $ok,
            HttpTransportException::class,
            'Unable to send an email: '.$errorMessage.sprintf(' (code %d).', $statusCode),
            $response
        );

        // Hyvor Relay Console API (POST /sends) responds with:
        // { "id": number, "message_id": string }
        $messageId = Arr::get($result, 'message_id');
        if (! is_scalar($messageId) || (string) $messageId === '') {
            throw new HttpTransportException(
                'Unable to send an email: unexpected API response (missing "message_id").'.sprintf(' (code %d).', $statusCode),
                $response
            );
        }

        $sentMessage->setMessageId((string) $messageId);

        return $response;
    }

    private function getPayload(Email $email, Envelope $envelope): array
    {
        $normalizeAddress = static function (Address $address): array|string {
            $name = $address->getName();
            if ($name === '') {
                return $address->getAddress();
            }

            return Arr::whereNotNull([
                'name' => $name,
                'email' => $address->getAddress(),
            ]);
        };

        $normalizeAddresses = static function (array $addresses) use ($normalizeAddress): array|string {
            $normalized = array_values(Arr::map($addresses, $normalizeAddress));

            return count($normalized) === 1 ? $normalized[0] : $normalized;
        };

        $from = $envelope->getSender() ?: ($email->getFrom()[0] ?? null);
        $to = $this->getRecipients($email, $envelope);

        $extra = $this->prepareHeadersAndTags($email->getHeaders());
        $headers = Arr::get($extra, 'headers');

        // Preserve Reply-To semantics as a normal header (SendRequest only supports "headers").
        if (($replyTo = $email->getReplyTo()) && is_array($headers)) {
            $headers['Reply-To'] ??= implode(', ', Arr::map($replyTo, fn (Address $a) => $a->toString()));
        } elseif (($replyTo = $email->getReplyTo())) {
            $headers = [
                'Reply-To' => implode(', ', Arr::map($replyTo, fn (Address $a) => $a->toString())),
            ];
        }

        return Arr::whereNotNull([
            'from' => $from ? $normalizeAddress($from) : null,
            'to' => $to ? $normalizeAddresses($to) : null,
            'cc' => ($cc = $email->getCc()) ? $normalizeAddresses($cc) : null,
            'bcc' => ($bcc = $email->getBcc()) ? $normalizeAddresses($bcc) : null,
            'subject' => ($email->getSubject() && $email->getSubject() !== 'default') ? $email->getSubject() : null,
            'body_html' => $email->getHtmlBody(),
            'body_text' => $email->getTextBody(),
            'headers' => $headers ?: null,
            'attachments' => ($attachments = $this->prepareAttachments($email)) ? $attachments : null,
        ]);
    }

    private function getIdempotencyKey(Email $email): ?string
    {
        $header = $email->getHeaders()->get('X-Idempotency-Key');
        if ($header === null) {
            return null;
        }

        $value = trim($header->getBodyAsString());
        $email->getHeaders()->remove('X-Idempotency-Key');

        return $value !== '' ? $value : null;
    }

    protected function stringifyAddresses(array $addresses): array
    {
        return Arr::map($addresses, fn (Address $address) => $this->stringifyAddress($address));
    }

    public function stringifyAddress(Address $address): array
    {
        return Arr::where([
            'email' => $address->getEncodedAddress(),
            'name' => $address->getName(),
        ], fn ($value) => ! empty($value));
    }

    private function prepareAttachments(Email $email): array
    {
        return Arr::map($email->getAttachments(), function (DataPart $attachment) {
            return [
                'content' => Str::replace("\r\n", '', $attachment->bodyToString()),
                'name' => $attachment->getPreparedHeaders()->getHeaderParameter('Content-Disposition', 'filename'),
            ];
        });
    }

    private function prepareHeadersAndTags(Headers $headers): array
    {
        $headersAndTags = [];
        $headersToBypass = ['from', 'sender', 'to', 'cc', 'bcc', 'subject', 'reply-to', 'content-type', 'accept', 'api-key'];

        $filteredHeaders = Arr::except([...$headers->all()], $headersToBypass);
        foreach ($filteredHeaders as $name => $header) {
            switch (true) {
                case $header instanceof TagHeader:
                    $headersAndTags['tags'][] = $header->getValue();
                    break;
                default:
                    $headersAndTags['headers'][$header->getName()] = $header->getBodyAsString();
            }

        }

        return $headersAndTags;
    }
}
