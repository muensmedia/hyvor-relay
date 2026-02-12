<?php

namespace Muensmedia\HyvorRelay\Data\Webhooks\Objects;

use Spatie\LaravelData\Data;

class BounceData extends Data
{
    public function __construct(
        public string $text,
        public string $status,
    ) {}
}
