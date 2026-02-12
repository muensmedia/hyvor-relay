<?php

namespace Muensmedia\HyvorRelay\Actions\Console\Suppressions;

use Lorisleiva\Actions\Concerns\AsObject;
use Muensmedia\HyvorRelay\Actions\Console\Concerns\InteractsWithConsoleApi;
use Muensmedia\HyvorRelay\Data\Console\Objects\SuppressionData;
use Spatie\LaravelData\DataCollection;

/**
 * @see https://relay.hyvor.com/docs/console-api#get-suppressions
 */
class GetSuppressionsAction
{
    use AsObject, InteractsWithConsoleApi;

    /**
     * @return DataCollection<int, SuppressionData>
     */
    public function handle(array $query = []): DataCollection
    {
        return SuppressionData::collect(
            $this->request('GET', 'suppressions', query: $query),
            DataCollection::class
        );
    }
}
