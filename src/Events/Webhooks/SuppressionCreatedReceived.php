<?php

namespace Muensmedia\HyvorRelay\Events\Webhooks;

use Illuminate\Foundation\Events\Dispatchable;
use Muensmedia\HyvorRelay\Data\Webhooks\Payloads\SuppressionPayloadData;

/**
 * Triggered when a suppression entry is created (for example after bounce/complaint).
 * Useful to mirror suppression lists and block future sends in your application.
 *
 * @see https://relay.hyvor.com/docs/webhooks#suppression-created
 */
class SuppressionCreatedReceived
{
    use Dispatchable;

    public function __construct(
        public SuppressionPayloadData $payload
    ) {}
}
