<?php

namespace Muensmedia\HyvorRelay\Actions\Console\Webhooks;

use Lorisleiva\Actions\Concerns\AsObject;
use Muensmedia\HyvorRelay\Actions\Console\Concerns\InteractsWithConsoleApi;
use Muensmedia\HyvorRelay\Data\Console\Objects\WebhookDeliveryData;
use Spatie\LaravelData\DataCollection;

/**
 * @see https://relay.hyvor.com/docs/console-api#get-webhook-deliveries
 */
class GetWebhookDeliveriesAction
{
    use AsObject, InteractsWithConsoleApi;

    /**
     * @return DataCollection<int, WebhookDeliveryData>
     */
    public function handle(array $query = []): DataCollection
    {
        return WebhookDeliveryData::collect(
            $this->request('GET', 'webhooks/deliveries', query: $query),
            DataCollection::class
        );
    }
}
