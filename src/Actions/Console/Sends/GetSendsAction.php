<?php

namespace Muensmedia\HyvorRelay\Actions\Console\Sends;

use Lorisleiva\Actions\Concerns\AsAction;
use Muensmedia\HyvorRelay\Actions\Console\Concerns\InteractsWithConsoleApi;
use Muensmedia\HyvorRelay\Data\Console\Objects\SendData;
use Spatie\LaravelData\DataCollection;

/**
 * @see https://relay.hyvor.com/docs/console-api#get-sends
 */
class GetSendsAction
{
    use AsAction;
    use InteractsWithConsoleApi;

    public function handle(array $query = []): DataCollection
    {
        return $this->toCollection(
            SendData::class,
            $this->request('GET', 'sends', query: $query)
        );
    }
}
