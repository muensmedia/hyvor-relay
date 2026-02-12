<?php

namespace Muensmedia\HyvorRelay\Actions\Console\ApiKeys;

use Lorisleiva\Actions\Concerns\AsAction;
use Muensmedia\HyvorRelay\HyvorRelay;

class GetApiKeysAction
{
    use AsAction;

    public function __construct(
        protected HyvorRelay $relay
    ) {}

    public function handle(): array
    {
        return $this->relay->getApiKeys();
    }
}
