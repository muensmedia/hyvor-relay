<?php

namespace Muensmedia\HyvorRelay\Actions\Console\Analytics;

use Lorisleiva\Actions\Concerns\AsAction;
use Muensmedia\HyvorRelay\Actions\Console\Concerns\InteractsWithConsoleApi;

class GetAnalyticsStatsAction
{
    use AsAction;
    use InteractsWithConsoleApi;

    public function handle(?string $period = null): array
    {
        return $this->request('GET', 'analytics/stats', query: $this->withoutNullValues([
            'period' => $period,
        ]));
    }
}
