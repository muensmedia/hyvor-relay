<?php

namespace Muensmedia\HyvorRelay\Actions\Console\Suppressions;

use Lorisleiva\Actions\Concerns\AsAction;
use Muensmedia\HyvorRelay\Actions\Console\Concerns\InteractsWithConsoleApi;

class DeleteSuppressionAction
{
    use AsAction;
    use InteractsWithConsoleApi;

    public function handle(int $id): array
    {
        return $this->request('DELETE', "suppressions/{$id}");
    }
}
