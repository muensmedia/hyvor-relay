<?php

namespace Muensmedia\HyvorRelay\Data\Webhooks\Payloads;

use Muensmedia\HyvorRelay\Data\Webhooks\Objects\BounceData;
use Muensmedia\HyvorRelay\Data\Webhooks\Objects\SendAttemptData;
use Muensmedia\HyvorRelay\Data\Webhooks\Objects\SendData;
use Muensmedia\HyvorRelay\Data\Webhooks\Objects\SendRecipientData;
use Spatie\LaravelData\Data;

/**
 * Payload DTO for send.recipient.bounced webhook events.
 *
 * @see https://relay.hyvor.com/docs/webhooks#send-recipient-bounced
 */
class SendRecipientBouncedPayloadData extends Data
{
    public function __construct(
        public SendData $send,
        public SendRecipientData $recipient,
        public ?SendAttemptData $attempt,
        public BounceData $bounce,
    ) {}
}
