<?php

namespace Muensmedia\HyvorRelay\Events\Webhooks;

use Illuminate\Foundation\Events\Dispatchable;
use Muensmedia\HyvorRelay\Data\Webhooks\Payloads\SuppressionPayloadData;

/**
 * Triggered when a suppression entry is removed from the Relay project.
 * Useful to re-enable eligible recipients in local suppression handling.
 *
 * @see https://relay.hyvor.com/docs/webhooks#suppression-deleted
 */
class SuppressionDeletedReceived
{
    use Dispatchable;

    public function __construct(
        public SuppressionPayloadData $payload
    ) {}
}
