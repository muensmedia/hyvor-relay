<?php

namespace Muensmedia\HyvorRelay\Actions\Console\Analytics;

use Lorisleiva\Actions\Concerns\AsAction;
use Muensmedia\HyvorRelay\Actions\Console\Concerns\InteractsWithConsoleApi;

class GetAnalyticsSendsChartAction
{
    use AsAction;
    use InteractsWithConsoleApi;

    public function handle(): array
    {
        return $this->request('GET', 'analytics/sends/chart');
    }
}
