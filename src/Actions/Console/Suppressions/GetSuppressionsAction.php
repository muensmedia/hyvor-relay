<?php

namespace Muensmedia\HyvorRelay\Actions\Console\Suppressions;

use Lorisleiva\Actions\Concerns\AsAction;
use Muensmedia\HyvorRelay\Actions\Console\Concerns\InteractsWithConsoleApi;

class GetSuppressionsAction
{
    use AsAction;
    use InteractsWithConsoleApi;

    public function handle(array $query = []): array
    {
        return $this->request('GET', 'suppressions', query: $query);
    }
}
