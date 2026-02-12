<?php

namespace Muensmedia\HyvorRelay\Data\Webhooks\Payloads;

use Muensmedia\HyvorRelay\Data\Webhooks\Objects\DomainData;
use Spatie\LaravelData\Data;

/**
 * Payload DTO for domain.created and domain.deleted webhook events.
 *
 * @see https://relay.hyvor.com/docs/webhooks#domain-created
 * @see https://relay.hyvor.com/docs/webhooks#domain-deleted
 */
class DomainPayloadData extends Data
{
    public function __construct(
        public DomainData $domain,
    ) {}
}
