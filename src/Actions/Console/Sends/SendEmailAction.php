<?php

namespace Muensmedia\HyvorRelay\Actions\Console\Sends;

use Lorisleiva\Actions\Concerns\AsAction;
use Muensmedia\HyvorRelay\Actions\Console\Concerns\InteractsWithConsoleApi;
use Muensmedia\HyvorRelay\Data\Console\Responses\SendEmailResponseData;

/**
 * @see https://relay.hyvor.com/docs/console-api#send-email
 */
class SendEmailAction
{
    use AsAction;
    use InteractsWithConsoleApi;

    public function handle(array $payload, ?string $idempotencyKey = null): SendEmailResponseData
    {
        $headers = [];

        if ($idempotencyKey !== null && $idempotencyKey !== '') {
            $headers['X-Idempotency-Key'] = $idempotencyKey;
        }

        return $this->toData(
            SendEmailResponseData::class,
            $this->request('POST', 'sends', json: $payload, headers: $headers)
        );
    }
}
