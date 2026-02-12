<?php

namespace Muensmedia\HyvorRelay\Actions\Console\Domains;

use Lorisleiva\Actions\Concerns\AsAction;
use Muensmedia\HyvorRelay\Actions\Console\Concerns\InteractsWithConsoleApi;

class GetDomainAction
{
    use AsAction;
    use InteractsWithConsoleApi;

    public function handle(?int $id = null, ?string $domain = null): array
    {
        return $this->request('GET', 'domains/by', query: $this->withoutNullValues([
            'id' => $id,
            'domain' => $domain,
        ]));
    }
}
