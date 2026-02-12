<?php

namespace Muensmedia\HyvorRelay\Actions\Console\Suppressions;

use Lorisleiva\Actions\Concerns\AsAction;
use Muensmedia\HyvorRelay\Actions\Console\Concerns\InteractsWithConsoleApi;
use Muensmedia\HyvorRelay\Data\Console\Objects\SuppressionData;
use Spatie\LaravelData\DataCollection;

/**
 * @see https://relay.hyvor.com/docs/console-api#get-suppressions
 */
class GetSuppressionsAction
{
    use AsAction;
    use InteractsWithConsoleApi;

    public function handle(array $query = []): DataCollection
    {
        return $this->toCollection(
            SuppressionData::class,
            $this->request('GET', 'suppressions', query: $query)
        );
    }
}
