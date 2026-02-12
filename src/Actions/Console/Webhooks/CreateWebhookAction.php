<?php

namespace Muensmedia\HyvorRelay\Actions\Console\Webhooks;

use Lorisleiva\Actions\Concerns\AsObject;
use Muensmedia\HyvorRelay\Actions\Console\Concerns\InteractsWithConsoleApi;
use Muensmedia\HyvorRelay\Data\Console\Objects\WebhookData;

/**
 * @see https://relay.hyvor.com/docs/console-api#create-webhook
 */
class CreateWebhookAction
{
    use AsObject, InteractsWithConsoleApi;

    public function handle(string $url, array $events, ?string $description = null): WebhookData
    {
        return WebhookData::from(
            $this->request('POST', 'webhooks', json: $this->withoutNullValues([
                'url' => $url,
                'events' => $events,
                'description' => $description,
            ]))
        );
    }
}
