<?php

namespace Muensmedia\HyvorRelay\Actions\Console\Domains;

use Lorisleiva\Actions\Concerns\AsAction;
use Muensmedia\HyvorRelay\Actions\Console\Concerns\InteractsWithConsoleApi;

class CreateDomainAction
{
    use AsAction;
    use InteractsWithConsoleApi;

    public function handle(string $domain): array
    {
        return $this->request('POST', 'domains', json: ['domain' => $domain]);
    }
}
