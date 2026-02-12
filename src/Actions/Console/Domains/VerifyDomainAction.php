<?php

namespace Muensmedia\HyvorRelay\Actions\Console\Domains;

use Lorisleiva\Actions\Concerns\AsAction;
use Muensmedia\HyvorRelay\Actions\Console\Concerns\InteractsWithConsoleApi;
use Muensmedia\HyvorRelay\Data\Console\Objects\DomainData;

/**
 * @see https://relay.hyvor.com/docs/console-api#verify-domain
 */
class VerifyDomainAction
{
    use AsAction;
    use InteractsWithConsoleApi;

    public function handle(?int $id = null, ?string $domain = null): DomainData
    {
        return $this->toData(
            DomainData::class,
            $this->request('POST', 'domains/verify', json: $this->withoutNullValues([
                'id' => $id,
                'domain' => $domain,
            ]))
        );
    }
}
