<?php

namespace Muensmedia\HyvorRelay\Data\Webhooks\Payloads;

use Muensmedia\HyvorRelay\Data\Webhooks\Objects\ComplaintData;
use Muensmedia\HyvorRelay\Data\Webhooks\Objects\SendData;
use Muensmedia\HyvorRelay\Data\Webhooks\Objects\SendRecipientData;
use Spatie\LaravelData\Data;

/**
 * Payload DTO for send.recipient.complained webhook events.
 *
 * @see https://relay.hyvor.com/docs/webhooks#send-recipient-complained
 */
class SendRecipientComplainedPayloadData extends Data
{
    public function __construct(
        public SendData $send,
        public SendRecipientData $recipient,
        public ComplaintData $complaint,
    ) {}
}
