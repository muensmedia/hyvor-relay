<?php

namespace Muensmedia\HyvorRelay\Actions\Console\Analytics;

use Lorisleiva\Actions\Concerns\AsObject;
use Muensmedia\HyvorRelay\Actions\Console\Concerns\InteractsWithConsoleApi;
use Muensmedia\HyvorRelay\Data\Console\Responses\AnalyticsSendsChartData;

/**
 * @see https://relay.hyvor.com/docs/api-console#get-analytics-chart
 */
class GetAnalyticsSendsChartAction
{
    use AsObject, InteractsWithConsoleApi;

    public function handle(): AnalyticsSendsChartData
    {
        return AnalyticsSendsChartData::from([
            'data' => $this->request('GET', 'analytics/sends/chart'),
        ]);
    }
}
