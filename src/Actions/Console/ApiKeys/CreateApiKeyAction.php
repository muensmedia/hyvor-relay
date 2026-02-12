<?php

namespace Muensmedia\HyvorRelay\Actions\Console\ApiKeys;

use Lorisleiva\Actions\Concerns\AsAction;
use Muensmedia\HyvorRelay\HyvorRelay;

class CreateApiKeyAction
{
    use AsAction;

    public function __construct(
        protected HyvorRelay $relay
    ) {}

    public function handle(string $name, array $scopes): array
    {
        return $this->relay->createApiKey($name, $scopes);
    }
}
