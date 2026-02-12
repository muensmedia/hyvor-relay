<?php

namespace Muensmedia\HyvorRelay\Actions\Console\Webhooks;

use Lorisleiva\Actions\Concerns\AsAction;
use Muensmedia\HyvorRelay\Actions\Console\Concerns\InteractsWithConsoleApi;
use Muensmedia\HyvorRelay\Data\Console\Objects\WebhookData;
use Spatie\LaravelData\DataCollection;

/**
 * @see https://relay.hyvor.com/docs/console-api#get-webhooks
 */
class GetWebhooksAction
{
    use AsAction;
    use InteractsWithConsoleApi;

    public function handle(): DataCollection
    {
        return $this->toCollection(
            WebhookData::class,
            $this->request('GET', 'webhooks')
        );
    }
}
