<?php

namespace Muensmedia\HyvorRelay\Data\Webhooks\Payloads;

use Muensmedia\HyvorRelay\Data\Webhooks\Objects\SendData;
use Muensmedia\HyvorRelay\Data\Webhooks\Objects\SendRecipientData;
use Muensmedia\HyvorRelay\Data\Webhooks\Objects\SuppressionData;
use Spatie\LaravelData\Data;

/**
 * Payload DTO for send.recipient.suppressed webhook events.
 *
 * @see https://relay.hyvor.com/docs/webhooks#send-recipient-suppressed
 */
class SendRecipientSuppressedPayloadData extends Data
{
    public function __construct(
        public SendData $send,
        public SendRecipientData $recipient,
        public SuppressionData $suppression,
    ) {}
}
