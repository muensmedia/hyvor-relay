<?php

namespace Muensmedia\HyvorRelay\Actions\Console\ApiKeys;

use Lorisleiva\Actions\Concerns\AsAction;
use Muensmedia\HyvorRelay\Actions\Console\Concerns\InteractsWithConsoleApi;
use Muensmedia\HyvorRelay\Data\Console\Responses\EmptyResponseData;

/**
 * @see https://relay.hyvor.com/docs/console-api#delete-api-key
 */
class DeleteApiKeyAction
{
    use AsAction;
    use InteractsWithConsoleApi;

    public function handle(int $id): EmptyResponseData
    {
        $this->request('DELETE', "api-keys/{$id}");

        return EmptyResponseData::from([]);
    }
}
