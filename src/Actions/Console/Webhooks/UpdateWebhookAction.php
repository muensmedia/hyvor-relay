<?php

namespace Muensmedia\HyvorRelay\Actions\Console\Webhooks;

use Lorisleiva\Actions\Concerns\AsAction;
use Muensmedia\HyvorRelay\Actions\Console\Concerns\InteractsWithConsoleApi;
use Muensmedia\HyvorRelay\Data\Console\Objects\WebhookData;

/**
 * @see https://relay.hyvor.com/docs/console-api#update-webhook
 */
class UpdateWebhookAction
{
    use AsAction;
    use InteractsWithConsoleApi;

    public function handle(int $id, array $payload): WebhookData
    {
        return $this->toData(
            WebhookData::class,
            $this->request('PATCH', "webhooks/{$id}", json: $payload)
        );
    }
}
