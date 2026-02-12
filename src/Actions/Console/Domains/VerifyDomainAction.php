<?php

namespace Muensmedia\HyvorRelay\Actions\Console\Domains;

use Lorisleiva\Actions\Concerns\AsObject;
use Muensmedia\HyvorRelay\Actions\Console\Concerns\InteractsWithConsoleApi;
use Muensmedia\HyvorRelay\Data\Console\Objects\DomainData;

/**
 * @see https://relay.hyvor.com/docs/api-console#verify-domain
 */
class VerifyDomainAction
{
    use AsObject, InteractsWithConsoleApi;

    public function handle(?int $id = null, ?string $domain = null): DomainData
    {
        return DomainData::from(
            $this->request('POST', 'domains/verify', json: $this->withoutNullValues([
                'id' => $id,
                'domain' => $domain,
            ]))
        );
    }
}
