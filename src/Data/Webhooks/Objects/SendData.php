<?php

namespace Muensmedia\HyvorRelay\Data\Webhooks\Objects;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

/**
 * DTO for the Hyvor Relay Send object used in webhook payloads.
 *
 * @see https://relay.hyvor.com/docs/api-console#send-object
 */
class SendData extends Data
{
    public function __construct(
        public int $id,
        public string $uuid,
        #[MapInputName('created_at')]
        public int $createdAt,
        #[MapInputName('from_address')]
        public string $fromAddress,
        #[MapInputName('from_name')]
        public ?string $fromName,
        public ?string $subject,
        #[MapInputName('body_html')]
        public ?string $bodyHtml,
        #[MapInputName('body_text')]
        public ?string $bodyText,
        public array $headers,
        public ?string $raw,
        #[MapInputName('size_bytes')]
        public ?int $sizeBytes,
        public bool $queued,
        #[MapInputName('send_after')]
        public int $sendAfter,
        #[DataCollectionOf(SendRecipientData::class)]
        public DataCollection $recipients,
        #[DataCollectionOf(SendAttemptData::class)]
        public DataCollection $attempts,
        public array $feedback,
    ) {}
}
