<?php

namespace Muensmedia\HyvorRelay\Actions\Console\Sends;

use Lorisleiva\Actions\Concerns\AsAction;
use Muensmedia\HyvorRelay\Actions\Console\Concerns\InteractsWithConsoleApi;

class SendEmailAction
{
    use AsAction;
    use InteractsWithConsoleApi;

    public function handle(array $payload, ?string $idempotencyKey = null): array
    {
        $headers = [];

        if ($idempotencyKey !== null && $idempotencyKey !== '') {
            $headers['X-Idempotency-Key'] = $idempotencyKey;
        }

        return $this->request('POST', 'sends', json: $payload, headers: $headers);
    }
}
