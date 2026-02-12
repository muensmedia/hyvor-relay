<?php

namespace Muensmedia\HyvorRelay\Actions\Console\Sends;

use Lorisleiva\Actions\Concerns\AsAction;
use Muensmedia\HyvorRelay\Actions\Console\Concerns\InteractsWithConsoleApi;

class GetSendByIdAction
{
    use AsAction;
    use InteractsWithConsoleApi;

    public function handle(int $id): array
    {
        return $this->request('GET', "sends/{$id}");
    }
}
