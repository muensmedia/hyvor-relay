<?php

namespace Muensmedia\HyvorRelay\Actions\Console\ApiKeys;

use Lorisleiva\Actions\Concerns\AsAction;
use Muensmedia\HyvorRelay\Actions\Console\Concerns\InteractsWithConsoleApi;
use Muensmedia\HyvorRelay\Data\Console\Objects\ApiKeyData;
use Spatie\LaravelData\DataCollection;

/**
 * @see https://relay.hyvor.com/docs/console-api#get-api-keys
 */
class GetApiKeysAction
{
    use AsAction;
    use InteractsWithConsoleApi;

    public function handle(): DataCollection
    {
        return $this->toCollection(
            ApiKeyData::class,
            $this->request('GET', 'api-keys')
        );
    }
}
