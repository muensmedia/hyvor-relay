<?php

namespace Muensmedia\HyvorRelay\Actions\Console\Sends;

use Lorisleiva\Actions\Concerns\AsAction;
use Muensmedia\HyvorRelay\Actions\Console\Concerns\InteractsWithConsoleApi;

class GetSendByUuidAction
{
    use AsAction;
    use InteractsWithConsoleApi;

    public function handle(string $uuid): array
    {
        return $this->request('GET', "sends/uuid/{$uuid}");
    }
}
