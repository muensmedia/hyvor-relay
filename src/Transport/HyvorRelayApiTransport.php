<?php

// original file: https://github.com/symfony/sendinblue-mailer/blob/6.3/Transport/SendinblueApiTransport.php

namespace Muensmedia\HyvorRelay\Transport;

use Illuminate\Support\Arr;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\Envelope;
use Symfony\Component\Mailer\Exception\HttpTransportException;
use Symfony\Component\Mailer\Header\MetadataHeader;
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

/**
 * @author Yann LUCAS
 */
final class HyvorRelayApiTransport extends AbstractApiTransport
{
    private string $key;

    public function __construct(string $key, HttpClientInterface $client = null, EventDispatcherInterface $dispatcher = null, LoggerInterface $logger = null)
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
        return ($this->host ?: config('hyvor-relay.endpoint')) . ($this->port ? ':' . $this->port : '');
    }

    protected function doSendApi(SentMessage $sentMessage, Email $email, Envelope $envelope): ResponseInterface
    {
        $response = $this->client->request('POST', $this->getEndpoint() . '/api/console/sends', [
            'json' => $this->getPayload($email, $envelope),
            'headers' => [
                'Authorization' => 'Bearer '.$this->key,
            ],
        ]);

        try {
            $statusCode = $response->getStatusCode();
            $result = $response->toArray(false);
        } catch (DecodingExceptionInterface) {
            throw new HttpTransportException('Unable to send an email: ' . $response->getContent(false) . sprintf(' (code %d).', $statusCode), $response);
        } catch (TransportExceptionInterface $e) {
            throw new HttpTransportException('Could not reach the remote Sendinblue server.', $response, 0, $e);
        }

        throw_if(
            201 !== $statusCode,
            HttpTransportException::class,
            'Unable to send an email: ' . Arr::get($result, 'message', '') . sprintf(' (code %d).', $statusCode), $response
        );

        $sentMessage->setMessageId(Arr::get($result, 'messageId', ''));

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

    protected function stringifyAddresses(array $addresses): array
    {
        return Arr::map($addresses, fn(Address $address) => $this->stringifyAddress($address));
    }

    public function stringifyAddress(Address $address): array
    {
        return Arr::where([
            'email' => $address->getEncodedAddress(),
            'name' => $address->getName(),
        ], fn($value) => !empty($value));
    }

    private function prepareAttachments(Email $email): array
    {
        return Arr::map($email->getAttachments(), function (DataPart $attachment) {
            return [
                'content' => str_replace("\r\n", '', $attachment->bodyToString()),
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
                case  $header instanceof TagHeader:
                    $headersAndTags['tags'][] = $header->getValue();
                    break;
                case $header instanceof MetadataHeader:
                    // does not work at the moment
                    $headersAndTags['headers']['X-Mailin-' . $header->getKey()] = $header->getValue();
                    break;
                case 'templateid' === $name:
                    $headersAndTags[$header->getName()] = (int)$header->getValue();
                    // cast it to string as brevo does not accept an integer as custom header value
                    $headersAndTags['headers']['X-Mailin-Template-Id'] = "".(int)$header->getValue();
                    break;
                case 'params' === $name:
                    $headersAndTags[$header->getName()] = json_decode($header->getValue(), true);
                    break;
                default:
                    $headersAndTags['headers'][$header->getName()] = $header->getBodyAsString();
            }

        }

        if (Arr::get($headersAndTags, 'headers.X-Mailin-Custom', false))
            $headersAndTags['headers']['X-Mailin-Custom'] = print_r($headersAndTags['headers']['X-Mailin-Custom'], true);

        return $headersAndTags;
    }
}
