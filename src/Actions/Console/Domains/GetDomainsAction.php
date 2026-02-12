<?php

namespace Muensmedia\HyvorRelay\Actions\Console\Domains;

use Lorisleiva\Actions\Concerns\AsAction;
use Muensmedia\HyvorRelay\Actions\Console\Concerns\InteractsWithConsoleApi;

class GetDomainsAction
{
    use AsAction;
    use InteractsWithConsoleApi;

    public function handle(array $query = []): array
    {
        return $this->request('GET', 'domains', query: $query);
    }
}
