<?php

namespace Muensmedia\HyvorRelay\Data\Webhooks\Payloads;

use Muensmedia\HyvorRelay\Data\Webhooks\Objects\SendAttemptData;
use Muensmedia\HyvorRelay\Data\Webhooks\Objects\SendData;
use Muensmedia\HyvorRelay\Data\Webhooks\Objects\SendRecipientData;
use Spatie\LaravelData\Data;

/**
 * Payload DTO for send.recipient.accepted, send.recipient.deferred and send.recipient.failed.
 *
 * @see https://relay.hyvor.com/docs/webhooks#send-recipient-accepted
 * @see https://relay.hyvor.com/docs/webhooks#send-recipient-deferred
 * @see https://relay.hyvor.com/docs/webhooks#send-recipient-failed
 */
class SendRecipientAttemptPayloadData extends Data
{
    public function __construct(
        public SendData $send,
        public SendRecipientData $recipient,
        public SendAttemptData $attempt,
    ) {}
}
