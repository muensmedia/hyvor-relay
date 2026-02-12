<?php

namespace Muensmedia\HyvorRelay\Actions\Console\Webhooks;

use Lorisleiva\Actions\Concerns\AsObject;
use Muensmedia\HyvorRelay\Actions\Console\Concerns\InteractsWithConsoleApi;
use Muensmedia\HyvorRelay\Data\Console\Responses\EmptyResponseData;

/**
 * @see https://relay.hyvor.com/docs/console-api#delete-webhook
 */
class DeleteWebhookAction
{
    use AsObject, InteractsWithConsoleApi;

    public function handle(int $id): EmptyResponseData
    {
        $this->request('DELETE', "webhooks/{$id}");

        return EmptyResponseData::from([]);
    }
}
