<?php

namespace Muensmedia\HyvorRelay\Actions\Console\Webhooks;

use Lorisleiva\Actions\Concerns\AsAction;
use Muensmedia\HyvorRelay\Actions\Console\Concerns\InteractsWithConsoleApi;

class UpdateWebhookAction
{
    use AsAction;
    use InteractsWithConsoleApi;

    public function handle(int $id, array $payload): array
    {
        return $this->request('PATCH', "webhooks/{$id}", json: $payload);
    }
}
