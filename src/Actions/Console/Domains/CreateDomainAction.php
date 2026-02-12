<?php

namespace Muensmedia\HyvorRelay\Actions\Console\Domains;

use Lorisleiva\Actions\Concerns\AsObject;
use Muensmedia\HyvorRelay\Actions\Console\Concerns\InteractsWithConsoleApi;
use Muensmedia\HyvorRelay\Data\Console\Objects\DomainData;

/**
 * @see https://relay.hyvor.com/docs/api-console#create-domain
 */
class CreateDomainAction
{
    use AsObject, InteractsWithConsoleApi;

    public function handle(string $domain): DomainData
    {
        return DomainData::from(
            $this->request('POST', 'domains', json: ['domain' => $domain])
        );
    }
}
