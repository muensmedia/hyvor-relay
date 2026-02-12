<?php

namespace Muensmedia\HyvorRelay\Actions\Console\Analytics;

use Lorisleiva\Actions\Concerns\AsAction;
use Muensmedia\HyvorRelay\HyvorRelay;

class GetAnalyticsStatsAction
{
    use AsAction;

    public function __construct(
        protected HyvorRelay $relay
    ) {}

    public function handle(?string $period = null): array
    {
        return $this->relay->getAnalyticsStats($period);
    }
}
