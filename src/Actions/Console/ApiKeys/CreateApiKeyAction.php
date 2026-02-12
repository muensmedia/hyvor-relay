<?php

namespace Muensmedia\HyvorRelay\Actions\Console\ApiKeys;

use Lorisleiva\Actions\Concerns\AsAction;
use Muensmedia\HyvorRelay\Actions\Console\Concerns\InteractsWithConsoleApi;

class CreateApiKeyAction
{
    use AsAction;
    use InteractsWithConsoleApi;

    public function handle(string $name, array $scopes): array
    {
        return $this->request('POST', 'api-keys', json: [
            'name' => $name,
            'scopes' => $scopes,
        ]);
    }
}
