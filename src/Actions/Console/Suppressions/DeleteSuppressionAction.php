<?php

namespace Muensmedia\HyvorRelay\Actions\Console\Suppressions;

use Lorisleiva\Actions\Concerns\AsAction;
use Muensmedia\HyvorRelay\HyvorRelay;

class DeleteSuppressionAction
{
    use AsAction;

    public function __construct(
        protected HyvorRelay $relay
    ) {}

    public function handle(int $id): array
    {
        return $this->relay->deleteSuppression($id);
    }
}
