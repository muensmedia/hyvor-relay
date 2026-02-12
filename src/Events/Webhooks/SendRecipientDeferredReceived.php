<?php

namespace Muensmedia\HyvorRelay\Events\Webhooks;

use Illuminate\Foundation\Events\Dispatchable;
use Muensmedia\HyvorRelay\Data\Webhooks\Payloads\SendRecipientAttemptPayloadData;

/**
 * Triggered when delivery is temporarily deferred by the recipient SMTP server.
 * Useful to keep a send in retry/pending state until accepted or bounced.
 *
 * @see https://relay.hyvor.com/docs/webhooks#send-recipient-deferred
 */
class SendRecipientDeferredReceived
{
    use Dispatchable;

    public function __construct(
        public SendRecipientAttemptPayloadData $payload
    ) {}
}
