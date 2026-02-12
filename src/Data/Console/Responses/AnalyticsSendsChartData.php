<?php

namespace Muensmedia\HyvorRelay\Data\Console\Responses;

use Spatie\LaravelData\Data;

class AnalyticsSendsChartData extends Data
{
    public function __construct(
        public array $data,
    ) {}
}
