<?php

namespace Muensmedia\HyvorRelay\Actions\Console\ApiKeys;

use Lorisleiva\Actions\Concerns\AsObject;
use Muensmedia\HyvorRelay\Actions\Console\Concerns\InteractsWithConsoleApi;
use Muensmedia\HyvorRelay\Data\Console\Objects\ApiKeyData;
use Spatie\LaravelData\DataCollection;

/**
 * @see https://relay.hyvor.com/docs/api-console#get-api-keys
 */
class GetApiKeysAction
{
    use AsObject, InteractsWithConsoleApi;

    /**
     * @return DataCollection<int, ApiKeyData>
     */
    public function handle(): DataCollection
    {
        return ApiKeyData::collect(
            $this->request('GET', 'api-keys'),
            DataCollection::class
        );
    }
}
