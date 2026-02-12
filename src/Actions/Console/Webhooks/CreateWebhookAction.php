<?php

namespace Muensmedia\HyvorRelay\Actions\Console\Webhooks;

use Lorisleiva\Actions\Concerns\AsAction;
use Muensmedia\HyvorRelay\Actions\Console\Concerns\InteractsWithConsoleApi;

class CreateWebhookAction
{
    use AsAction;
    use InteractsWithConsoleApi;

    public function handle(string $url, array $events, ?string $description = null): array
    {
        return $this->request('POST', 'webhooks', json: $this->withoutNullValues([
            'url' => $url,
            'events' => $events,
            'description' => $description,
        ]));
    }
}
