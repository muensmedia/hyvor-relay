<?php

namespace Muensmedia\HyvorRelay\Data\Console\Responses;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

class AnalyticsStatsData extends Data
{
    public function __construct(
        public int $sends,
        #[MapInputName('bounce_rate')]
        public float $bounceRate,
        #[MapInputName('complaint_rate')]
        public float $complaintRate,
    ) {}
}
