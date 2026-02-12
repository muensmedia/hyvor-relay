<?php

namespace Muensmedia\HyvorRelay\Actions\Console\Sends;

use Lorisleiva\Actions\Concerns\AsAction;
use Muensmedia\HyvorRelay\HyvorRelay;

class SendEmailAction
{
    use AsAction;

    public function __construct(
        protected HyvorRelay $relay
    ) {}

    public function handle(array $payload, ?string $idempotencyKey = null): array
    {
        return $this->relay->sendEmail($payload, $idempotencyKey);
    }
}
