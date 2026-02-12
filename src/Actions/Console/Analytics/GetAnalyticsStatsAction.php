<?php

namespace Muensmedia\HyvorRelay\Actions\Console\Analytics;

use Lorisleiva\Actions\Concerns\AsAction;
use Muensmedia\HyvorRelay\Actions\Console\Concerns\InteractsWithConsoleApi;
use Muensmedia\HyvorRelay\Data\Console\Responses\AnalyticsStatsData;

/**
 * @see https://relay.hyvor.com/docs/console-api#get-analytics-statistics
 */
class GetAnalyticsStatsAction
{
    use AsAction;
    use InteractsWithConsoleApi;

    public function handle(?string $period = null): AnalyticsStatsData
    {
        return $this->toData(
            AnalyticsStatsData::class,
            $this->request('GET', 'analytics/stats', query: $this->withoutNullValues([
                'period' => $period,
            ]))
        );
    }
}
