<?php

namespace Muensmedia\HyvorRelay\Events\Webhooks;

use Illuminate\Foundation\Events\Dispatchable;
use Muensmedia\HyvorRelay\Data\Webhooks\Payloads\SendRecipientBouncedPayloadData;

/**
 * Triggered when delivery is permanently rejected (hard/soft bounce context).
 * Useful to flag invalid recipients and trigger cleanup or suppression workflows.
 *
 * @see https://relay.hyvor.com/docs/webhooks#send-recipient-bounced
 */
class SendRecipientBouncedReceived
{
    use Dispatchable;

    public function __construct(
        public SendRecipientBouncedPayloadData $payload
    ) {}
}
