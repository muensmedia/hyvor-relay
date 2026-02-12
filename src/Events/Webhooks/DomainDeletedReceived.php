<?php

namespace Muensmedia\HyvorRelay\Events\Webhooks;

use Illuminate\Foundation\Events\Dispatchable;
use Muensmedia\HyvorRelay\Data\Webhooks\Payloads\DomainPayloadData;

/**
 * Triggered when a domain is removed from the Relay project.
 * Useful to clean up local domain configuration and disable related sends.
 *
 * @see https://relay.hyvor.com/docs/webhooks#domain-deleted
 */
class DomainDeletedReceived
{
    use Dispatchable;

    public function __construct(
        public DomainPayloadData $payload
    ) {}
}
