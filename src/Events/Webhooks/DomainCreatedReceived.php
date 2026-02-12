<?php

namespace Muensmedia\HyvorRelay\Events\Webhooks;

use Illuminate\Foundation\Events\Dispatchable;
use Muensmedia\HyvorRelay\Data\Webhooks\Payloads\DomainPayloadData;

/**
 * Triggered when a new domain is added to the Relay project.
 * Useful to initialize local domain records and verification checks.
 *
 * @see https://relay.hyvor.com/docs/webhooks#domain-created
 */
class DomainCreatedReceived
{
    use Dispatchable;

    public function __construct(
        public DomainPayloadData $payload
    ) {}
}
