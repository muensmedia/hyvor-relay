<?php

namespace Muensmedia\HyvorRelay\Actions\Console\ApiKeys;

use Lorisleiva\Actions\Concerns\AsAction;
use Muensmedia\HyvorRelay\Actions\Console\Concerns\InteractsWithConsoleApi;

class DeleteApiKeyAction
{
    use AsAction;
    use InteractsWithConsoleApi;

    public function handle(int $id): array
    {
        return $this->request('DELETE', "api-keys/{$id}");
    }
}
