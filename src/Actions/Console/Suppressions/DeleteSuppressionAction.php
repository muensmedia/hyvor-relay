<?php

namespace Muensmedia\HyvorRelay\Actions\Console\Suppressions;

use Lorisleiva\Actions\Concerns\AsObject;
use Muensmedia\HyvorRelay\Actions\Console\Concerns\InteractsWithConsoleApi;
use Muensmedia\HyvorRelay\Data\Console\Responses\EmptyResponseData;

/**
 * @see https://relay.hyvor.com/docs/api-console#delete-suppression
 */
class DeleteSuppressionAction
{
    use AsObject, InteractsWithConsoleApi;

    public function handle(int $id): EmptyResponseData
    {
        $this->request('DELETE', "suppressions/{$id}");

        return EmptyResponseData::from([]);
    }
}
