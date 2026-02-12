<?php

namespace Muensmedia\HyvorRelay\Actions\Console\Analytics;

use Lorisleiva\Actions\Concerns\AsObject;
use Muensmedia\HyvorRelay\Actions\Console\Concerns\InteractsWithConsoleApi;
use Muensmedia\HyvorRelay\Data\Console\Responses\AnalyticsStatsData;

/**
 * @see https://relay.hyvor.com/docs/api-console#get-analytics-stats
 */
class GetAnalyticsStatsAction
{
    use AsObject, InteractsWithConsoleApi;

    public function handle(?string $period = null): AnalyticsStatsData
    {
        return AnalyticsStatsData::from(
            $this->request('GET', 'analytics/stats', query: $this->withoutNullValues([
                'period' => $period,
            ]))
        );
    }
}
