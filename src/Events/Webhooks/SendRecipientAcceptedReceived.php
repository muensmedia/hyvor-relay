<?php

namespace Muensmedia\HyvorRelay\Events\Webhooks;

use Illuminate\Foundation\Events\Dispatchable;
use Muensmedia\HyvorRelay\Data\Webhooks\Payloads\SendRecipientAttemptPayloadData;

/**
 * Triggered when the recipient SMTP server accepts an email.
 * Useful to mark delivery to server as successful and continue tracking final outcome.
 *
 * @see https://relay.hyvor.com/docs/webhooks#send-recipient-accepted
 */
class SendRecipientAcceptedReceived
{
    use Dispatchable;

    public function __construct(
        public SendRecipientAttemptPayloadData $payload
    ) {}
}
