<?php

namespace Muensmedia\HyvorRelay\Actions\Console\Suppressions;

use Lorisleiva\Actions\Concerns\AsAction;
use Muensmedia\HyvorRelay\HyvorRelay;

class GetSuppressionsAction
{
    use AsAction;

    public function __construct(
        protected HyvorRelay $relay
    ) {}

    public function handle(array $query = []): array
    {
        return $this->relay->getSuppressions($query);
    }
}
