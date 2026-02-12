<?php

namespace Muensmedia\HyvorRelay\Actions\Console\ApiKeys;

use Lorisleiva\Actions\Concerns\AsAction;
use Muensmedia\HyvorRelay\HyvorRelay;

class UpdateApiKeyAction
{
    use AsAction;

    public function __construct(
        protected HyvorRelay $relay
    ) {}

    public function handle(int $id, array $payload): array
    {
        return $this->relay->updateApiKey($id, $payload);
    }
}
