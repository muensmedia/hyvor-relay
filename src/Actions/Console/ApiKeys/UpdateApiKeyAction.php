<?php

namespace Muensmedia\HyvorRelay\Actions\Console\ApiKeys;

use Lorisleiva\Actions\Concerns\AsAction;
use Muensmedia\HyvorRelay\Actions\Console\Concerns\InteractsWithConsoleApi;

class UpdateApiKeyAction
{
    use AsAction;
    use InteractsWithConsoleApi;

    public function handle(int $id, array $payload): array
    {
        return $this->request('PATCH', "api-keys/{$id}", json: $payload);
    }
}
