<?php

namespace Muensmedia\HyvorRelay\Actions\Console\Sends;

use Lorisleiva\Actions\Concerns\AsAction;
use Muensmedia\HyvorRelay\Actions\Console\Concerns\InteractsWithConsoleApi;
use Muensmedia\HyvorRelay\Data\Console\Objects\SendData;

/**
 * @see https://relay.hyvor.com/docs/console-api#get-send-by-uuid
 */
class GetSendByUuidAction
{
    use AsAction;
    use InteractsWithConsoleApi;

    public function handle(string $uuid): SendData
    {
        return $this->toData(
            SendData::class,
            $this->request('GET', "sends/uuid/{$uuid}")
        );
    }
}
