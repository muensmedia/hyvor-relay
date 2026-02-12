<?php

namespace Muensmedia\HyvorRelay\Actions\Console\Webhooks;

use Lorisleiva\Actions\Concerns\AsObject;
use Muensmedia\HyvorRelay\Actions\Console\Concerns\InteractsWithConsoleApi;
use Muensmedia\HyvorRelay\Data\Console\Objects\WebhookData;
use Spatie\LaravelData\DataCollection;

/**
 * @see https://relay.hyvor.com/docs/console-api#get-webhooks
 */
class GetWebhooksAction
{
    use AsObject, InteractsWithConsoleApi;

    public function handle(): DataCollection
    {
        return WebhookData::collect(
            $this->request('GET', 'webhooks'),
            DataCollection::class
        );
    }
}
