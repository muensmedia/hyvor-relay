<?php

namespace Muensmedia\HyvorRelay\Data\Console\Requests;

use InvalidArgumentException;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

class SendEmailPayloadData extends Data
{
    public function __construct(
        public SendEmailAddressData|string $from,
        public SendEmailAddressData|string|array $to,
        public SendEmailAddressData|string|array|null $cc = null,
        public SendEmailAddressData|string|array|null $bcc = null,
        public ?string $subject = null,
        #[MapInputName('body_html')]
        public ?string $bodyHtml = null,
        #[MapInputName('body_text')]
        public ?string $bodyText = null,
        public ?array $headers = null,
        public ?array $attachments = null,
    ) {}

    /**
     * @return array{
     *     from: array{email: string, name?: string}|string,
     *     to: array<int, array{email: string, name?: string}|string>|array{email: string, name?: string}|string,
     *     cc?: array<int, array{email: string, name?: string}|string>|array{email: string, name?: string}|string,
     *     bcc?: array<int, array{email: string, name?: string}|string>|array{email: string, name?: string}|string,
     *     subject?: string,
     *     body_html?: string,
     *     body_text?: string,
     *     headers?: array<string, string>,
     *     attachments?: array<int, array{content: string, name?: string, content_type?: string}>
     * }
     */
    public function toApiPayload(): array
    {
        return array_filter([
            'from' => $this->normalizeAddress($this->from),
            'to' => $this->normalizeAddressOrAddresses($this->to),
            'cc' => $this->normalizeAddressOrAddresses($this->cc),
            'bcc' => $this->normalizeAddressOrAddresses($this->bcc),
            'subject' => $this->subject,
            'body_html' => $this->bodyHtml,
            'body_text' => $this->bodyText,
            'headers' => $this->headers,
            'attachments' => $this->normalizeAttachments(),
        ], static fn (mixed $value): bool => $value !== null);
    }

    /**
     * @return array{email: string, name?: string}|string
     */
    private function normalizeAddress(SendEmailAddressData|string|array $address): array|string
    {
        if (is_string($address)) {
            return $address;
        }

        if ($address instanceof SendEmailAddressData) {
            return $address->toApiPayload();
        }

        if (isset($address['email']) && is_string($address['email'])) {
            return SendEmailAddressData::from($address)->toApiPayload();
        }

        throw new InvalidArgumentException('Invalid address payload provided.');
    }

    /**
     * @return array<int, array{email: string, name?: string}|string>|array{email: string, name?: string}|string|null
     */
    private function normalizeAddressOrAddresses(SendEmailAddressData|string|array|null $addresses): array|string|null
    {
        if ($addresses === null) {
            return null;
        }

        if ($addresses instanceof SendEmailAddressData || is_string($addresses)) {
            return $this->normalizeAddress($addresses);
        }

        if (isset($addresses['email']) && is_string($addresses['email'])) {
            return $this->normalizeAddress($addresses);
        }

        if (! array_is_list($addresses)) {
            throw new InvalidArgumentException('Invalid list of addresses provided.');
        }

        return array_map(
            fn (SendEmailAddressData|string|array $address): array|string => $this->normalizeAddress($address),
            $addresses
        );
    }

    /**
     * @return array<int, array{content: string, name?: string, content_type?: string}>|null
     */
    private function normalizeAttachments(): ?array
    {
        if ($this->attachments === null) {
            return null;
        }

        return array_map(function (SendEmailAttachmentData|array $attachment): array {
            if ($attachment instanceof SendEmailAttachmentData) {
                return $attachment->toApiPayload();
            }

            return SendEmailAttachmentData::from($attachment)->toApiPayload();
        }, $this->attachments);
    }
}
