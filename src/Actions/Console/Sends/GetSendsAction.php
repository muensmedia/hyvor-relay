<?php

namespace Muensmedia\HyvorRelay\Actions\Console\Sends;

use Lorisleiva\Actions\Concerns\AsAction;
use Muensmedia\HyvorRelay\Actions\Console\Concerns\InteractsWithConsoleApi;

class GetSendsAction
{
    use AsAction;
    use InteractsWithConsoleApi;

    public function handle(array $query = []): array
    {
        return $this->request('GET', 'sends', query: $query);
    }
}
