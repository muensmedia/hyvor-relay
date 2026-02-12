<?php

namespace Muensmedia\HyvorRelay\Actions\Console\Sends;

use Lorisleiva\Actions\Concerns\AsObject;
use Muensmedia\HyvorRelay\Actions\Console\Concerns\InteractsWithConsoleApi;
use Muensmedia\HyvorRelay\Data\Console\Objects\SendData;

/**
 * @see https://relay.hyvor.com/docs/console-api#get-send-by-uuid
 */
class GetSendByUuidAction
{
    use AsObject, InteractsWithConsoleApi;

    public function handle(string $uuid): SendData
    {
        return SendData::from(
            $this->request('GET', "sends/uuid/{$uuid}")
        );
    }
}
