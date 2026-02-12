<?php

namespace Muensmedia\HyvorRelay\Actions\Console\ApiKeys;

use Lorisleiva\Actions\Concerns\AsAction;
use Muensmedia\HyvorRelay\Actions\Console\Concerns\InteractsWithConsoleApi;

class GetApiKeysAction
{
    use AsAction;
    use InteractsWithConsoleApi;

    public function handle(): array
    {
        return $this->request('GET', 'api-keys');
    }
}
