<?php

namespace Muensmedia\HyvorRelay\Events\Webhooks;

use Illuminate\Foundation\Events\Dispatchable;
use Muensmedia\HyvorRelay\Data\Webhooks\Payloads\SendRecipientAttemptPayloadData;

/**
 * Triggered when delivery fails after retries due to transient/system issues.
 * Useful to alert operations and schedule fallback retry logic in your app.
 *
 * @see https://relay.hyvor.com/docs/webhooks#send-recipient-failed
 */
class SendRecipientFailedReceived
{
    use Dispatchable;

    public function __construct(
        public SendRecipientAttemptPayloadData $payload
    ) {}
}
