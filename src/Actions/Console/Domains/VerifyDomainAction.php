<?php

namespace Muensmedia\HyvorRelay\Actions\Console\Domains;

use Lorisleiva\Actions\Concerns\AsAction;
use Muensmedia\HyvorRelay\HyvorRelay;

class VerifyDomainAction
{
    use AsAction;

    public function __construct(
        protected HyvorRelay $relay
    ) {}

    public function handle(?int $id = null, ?string $domain = null): array
    {
        return $this->relay->verifyDomain($id, $domain);
    }
}
