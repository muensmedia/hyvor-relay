<?php

namespace Muensmedia\HyvorRelay\Actions\Console\Webhooks;

use Lorisleiva\Actions\Concerns\AsAction;
use Muensmedia\HyvorRelay\Actions\Console\Concerns\InteractsWithConsoleApi;
use Muensmedia\HyvorRelay\Data\Console\Objects\WebhookDeliveryData;
use Spatie\LaravelData\DataCollection;

/**
 * @see https://relay.hyvor.com/docs/console-api#get-webhook-deliveries
 */
class GetWebhookDeliveriesAction
{
    use AsAction;
    use InteractsWithConsoleApi;

    public function handle(array $query = []): DataCollection
    {
        return $this->toCollection(
            WebhookDeliveryData::class,
            $this->request('GET', 'webhooks/deliveries', query: $query)
        );
    }
}
