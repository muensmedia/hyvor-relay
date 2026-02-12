<?php

namespace Muensmedia\HyvorRelay\Actions\Console\Sends;

use Lorisleiva\Actions\Concerns\AsObject;
use Muensmedia\HyvorRelay\Actions\Console\Concerns\InteractsWithConsoleApi;
use Muensmedia\HyvorRelay\Data\Console\Objects\SendData;

/**
 * @see https://relay.hyvor.com/docs/console-api#get-send-by-id
 */
class GetSendByIdAction
{
    use AsObject, InteractsWithConsoleApi;

    public function handle(int $id): SendData
    {
        return SendData::from(
            $this->request('GET', "sends/{$id}")
        );
    }
}
