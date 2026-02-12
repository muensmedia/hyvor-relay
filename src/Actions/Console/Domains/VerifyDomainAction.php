<?php

namespace Muensmedia\HyvorRelay\Actions\Console\Domains;

use Lorisleiva\Actions\Concerns\AsAction;
use Muensmedia\HyvorRelay\Actions\Console\Concerns\InteractsWithConsoleApi;

class VerifyDomainAction
{
    use AsAction;
    use InteractsWithConsoleApi;

    public function handle(?int $id = null, ?string $domain = null): array
    {
        return $this->request('POST', 'domains/verify', json: $this->withoutNullValues([
            'id' => $id,
            'domain' => $domain,
        ]));
    }
}
