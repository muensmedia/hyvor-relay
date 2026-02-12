<?php

namespace Muensmedia\HyvorRelay\Actions\Console\Domains;

use Lorisleiva\Actions\Concerns\AsObject;
use Muensmedia\HyvorRelay\Actions\Console\Concerns\InteractsWithConsoleApi;
use Muensmedia\HyvorRelay\Data\Console\Objects\DomainData;

/**
 * @see https://relay.hyvor.com/docs/api-console#get-domain
 */
class GetDomainAction
{
    use AsObject, InteractsWithConsoleApi;

    public function handle(?int $id = null, ?string $domain = null): DomainData
    {
        return DomainData::from(
            $this->request('GET', 'domains/by', query: $this->withoutNullValues([
                'id' => $id,
                'domain' => $domain,
            ]))
        );
    }
}
