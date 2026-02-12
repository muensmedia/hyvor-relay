<?php

namespace Muensmedia\HyvorRelay\Actions\Console\Sends;

use Lorisleiva\Actions\Concerns\AsObject;
use Muensmedia\HyvorRelay\Actions\Console\Concerns\InteractsWithConsoleApi;
use Muensmedia\HyvorRelay\Data\Console\Objects\SendData;
use Spatie\LaravelData\DataCollection;

/**
 * @see https://relay.hyvor.com/docs/api-console#get-sends
 */
class GetSendsAction
{
    use AsObject, InteractsWithConsoleApi;

    /**
     * @return DataCollection<int, SendData>
     */
    public function handle(array $query = []): DataCollection
    {
        return SendData::collect(
            $this->request('GET', 'sends', query: $query, apiKeyConfig: 'hyvor-relay.api_keys.send'),
            DataCollection::class
        );
    }
}
