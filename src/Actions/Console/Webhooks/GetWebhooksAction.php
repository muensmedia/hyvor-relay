<?php

namespace Muensmedia\HyvorRelay\Actions\Console\Webhooks;

use Lorisleiva\Actions\Concerns\AsObject;
use Muensmedia\HyvorRelay\Actions\Console\Concerns\InteractsWithConsoleApi;
use Muensmedia\HyvorRelay\Data\Console\Objects\WebhookData;
use Spatie\LaravelData\DataCollection;

/**
 * @see https://relay.hyvor.com/docs/api-console#get-webhooks
 */
class GetWebhooksAction
{
    use AsObject, InteractsWithConsoleApi;

    /**
     * @return DataCollection<int, WebhookData>
     */
    public function handle(): DataCollection
    {
        return WebhookData::collect(
            $this->request('GET', 'webhooks'),
            DataCollection::class
        );
    }
}
