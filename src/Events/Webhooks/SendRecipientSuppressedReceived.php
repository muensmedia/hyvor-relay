<?php

namespace Muensmedia\HyvorRelay\Events\Webhooks;

use Illuminate\Foundation\Events\Dispatchable;
use Muensmedia\HyvorRelay\Data\Webhooks\Payloads\SendRecipientSuppressedPayloadData;

/**
 * Triggered when sending is skipped because the recipient is already suppressed.
 * Useful to track blocked deliveries and keep local suppression state in sync.
 *
 * @see https://relay.hyvor.com/docs/webhooks#send-recipient-suppressed
 */
class SendRecipientSuppressedReceived
{
    use Dispatchable;

    public function __construct(
        public SendRecipientSuppressedPayloadData $payload
    ) {}
}
