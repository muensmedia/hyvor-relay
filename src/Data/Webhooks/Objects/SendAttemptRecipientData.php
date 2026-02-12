<?php

namespace Muensmedia\HyvorRelay\Data\Webhooks\Objects;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

/**
 * DTO for per-recipient SMTP result data inside a send attempt.
 *
 * @see https://relay.hyvor.com/docs/api-console#send-attempt-object
 */
class SendAttemptRecipientData extends Data
{
    public function __construct(
        public int $id,
        #[MapInputName('created_at')]
        public int $createdAt,
        #[MapInputName('recipient_id')]
        public int $recipientId,
        #[MapInputName('recipient_status')]
        public string $recipientStatus,
        #[MapInputName('smtp_code')]
        public int $smtpCode,
        #[MapInputName('smtp_enhanced_code')]
        public ?string $smtpEnhancedCode,
        #[MapInputName('smtp_message')]
        public string $smtpMessage,
        #[MapInputName('is_suppressed')]
        public bool $isSuppressed,
    ) {}
}
