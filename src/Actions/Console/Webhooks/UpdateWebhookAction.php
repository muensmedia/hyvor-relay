<?php

namespace Muensmedia\HyvorRelay\Actions\Console\Webhooks;

use Lorisleiva\Actions\Concerns\AsObject;
use Muensmedia\HyvorRelay\Actions\Console\Concerns\InteractsWithConsoleApi;
use Muensmedia\HyvorRelay\Data\Console\Objects\WebhookData;

/**
 * @see https://relay.hyvor.com/docs/console-api#update-webhook
 */
class UpdateWebhookAction
{
    use AsObject, InteractsWithConsoleApi;

    public function handle(int $id, array $payload): WebhookData
    {
        return WebhookData::from(
            $this->request('PATCH', "webhooks/{$id}", json: $payload)
        );
    }
}
