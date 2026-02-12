<?php

namespace Muensmedia\HyvorRelay\Events\Webhooks;

use Illuminate\Foundation\Events\Dispatchable;
use Muensmedia\HyvorRelay\Data\Webhooks\Payloads\DomainStatusChangedPayloadData;

/**
 * Triggered when a domain status changes (pending/active/warning/suspended).
 * Useful to enable or restrict sending behavior based on verification status.
 *
 * @see https://relay.hyvor.com/docs/webhooks#domain-status-changed
 */
class DomainStatusChangedReceived
{
    use Dispatchable;

    public function __construct(
        public DomainStatusChangedPayloadData $payload
    ) {}
}
