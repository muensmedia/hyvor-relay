<?php

namespace Muensmedia\HyvorRelay\Actions\Console\Analytics;

use Lorisleiva\Actions\Concerns\AsAction;
use Muensmedia\HyvorRelay\Actions\Console\Concerns\InteractsWithConsoleApi;
use Muensmedia\HyvorRelay\Data\Console\Responses\AnalyticsSendsChartData;

/**
 * @see https://relay.hyvor.com/docs/console-api#get-analytics-chart-data
 */
class GetAnalyticsSendsChartAction
{
    use AsAction;
    use InteractsWithConsoleApi;

    public function handle(): AnalyticsSendsChartData
    {
        return AnalyticsSendsChartData::from([
            'data' => $this->request('GET', 'analytics/sends/chart'),
        ]);
    }
}
