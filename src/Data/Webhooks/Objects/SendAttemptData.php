<?php

namespace Muensmedia\HyvorRelay\Data\Webhooks\Objects;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class SendAttemptData extends Data
{
    public function __construct(
        public int $id,
        #[MapInputName('created_at')]
        public int $createdAt,
        public string $status,
        #[MapInputName('try_count')]
        public int $tryCount,
        public ?string $domain,
        #[MapInputName('resolved_mx_hosts')]
        public array $resolvedMxHosts,
        #[MapInputName('responded_mx_host')]
        public ?string $respondedMxHost,
        #[MapInputName('smtp_conversations')]
        public array $smtpConversations,
        #[MapInputName('recipient_ids')]
        public ?array $recipientIds,
        #[DataCollectionOf(SendAttemptRecipientData::class)]
        public ?DataCollection $recipients,
        #[MapInputName('duration_ms')]
        public int $durationMs,
        public ?string $error,
    ) {}
}
