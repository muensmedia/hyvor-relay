<?php

namespace Muensmedia\HyvorRelay\Actions\Console\ApiKeys;

use Lorisleiva\Actions\Concerns\AsAction;
use Muensmedia\HyvorRelay\HyvorRelay;

class DeleteApiKeyAction
{
    use AsAction;

    public function __construct(
        protected HyvorRelay $relay
    ) {}

    public function handle(int $id): array
    {
        return $this->relay->deleteApiKey($id);
    }
}
