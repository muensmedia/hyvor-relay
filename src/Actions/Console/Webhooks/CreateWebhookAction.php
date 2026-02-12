<?php

namespace Muensmedia\HyvorRelay\Actions\Console\Webhooks;

use Lorisleiva\Actions\Concerns\AsAction;
use Muensmedia\HyvorRelay\Actions\Console\Concerns\InteractsWithConsoleApi;
use Muensmedia\HyvorRelay\Data\Console\Objects\WebhookData;

/**
 * @see https://relay.hyvor.com/docs/console-api#create-webhook
 */
class CreateWebhookAction
{
    use AsAction;
    use InteractsWithConsoleApi;

    public function handle(string $url, array $events, ?string $description = null): WebhookData
    {
        return $this->toData(
            WebhookData::class,
            $this->request('POST', 'webhooks', json: $this->withoutNullValues([
                'url' => $url,
                'events' => $events,
                'description' => $description,
            ]))
        );
    }
}
