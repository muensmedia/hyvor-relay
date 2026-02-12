<?php

namespace Muensmedia\HyvorRelay\Data\Webhooks\Payloads;

use Muensmedia\HyvorRelay\Data\Webhooks\Objects\DomainData;
use Spatie\LaravelData\Data;

class DomainPayloadData extends Data
{
    public function __construct(
        public DomainData $domain,
    ) {}
}
