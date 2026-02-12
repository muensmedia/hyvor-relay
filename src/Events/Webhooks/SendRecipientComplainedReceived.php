<?php

namespace Muensmedia\HyvorRelay\Events\Webhooks;

use Illuminate\Foundation\Events\Dispatchable;
use Muensmedia\HyvorRelay\Data\Webhooks\Payloads\SendRecipientComplainedPayloadData;

/**
 * Triggered when a recipient files a spam complaint (FBL).
 * Useful to stop future sends to the address and alert compliance/abuse handling.
 *
 * @see https://relay.hyvor.com/docs/webhooks#send-recipient-complained
 */
class SendRecipientComplainedReceived
{
    use Dispatchable;

    public function __construct(
        public SendRecipientComplainedPayloadData $payload
    ) {}
}
