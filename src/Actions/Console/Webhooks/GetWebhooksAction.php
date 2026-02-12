<?php

namespace Muensmedia\HyvorRelay\Actions\Console\Webhooks;

use Lorisleiva\Actions\Concerns\AsAction;
use Muensmedia\HyvorRelay\Actions\Console\Concerns\InteractsWithConsoleApi;

class GetWebhooksAction
{
    use AsAction;
    use InteractsWithConsoleApi;

    public function handle(): array
    {
        return $this->request('GET', 'webhooks');
    }
}
