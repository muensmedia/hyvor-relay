<?php

namespace Muensmedia\HyvorRelay\Actions\Console\Webhooks;

use Lorisleiva\Actions\Concerns\AsAction;
use Muensmedia\HyvorRelay\Actions\Console\Concerns\InteractsWithConsoleApi;

class DeleteWebhookAction
{
    use AsAction;
    use InteractsWithConsoleApi;

    public function handle(int $id): array
    {
        return $this->request('DELETE', "webhooks/{$id}");
    }
}
