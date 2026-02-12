<?php

namespace Muensmedia\HyvorRelay\Data\Webhooks\Payloads;

use Muensmedia\HyvorRelay\Data\Webhooks\Objects\SuppressionData;
use Spatie\LaravelData\Data;

class SuppressionPayloadData extends Data
{
    public function __construct(
        public SuppressionData $suppression,
    ) {}
}
