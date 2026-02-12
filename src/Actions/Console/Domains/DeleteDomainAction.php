<?php

namespace Muensmedia\HyvorRelay\Actions\Console\Domains;

use Lorisleiva\Actions\Concerns\AsObject;
use Muensmedia\HyvorRelay\Actions\Console\Concerns\InteractsWithConsoleApi;
use Muensmedia\HyvorRelay\Data\Console\Responses\EmptyResponseData;

/**
 * @see https://relay.hyvor.com/docs/console-api#delete-domain
 */
class DeleteDomainAction
{
    use AsObject, InteractsWithConsoleApi;

    public function handle(?int $id = null, ?string $domain = null): EmptyResponseData
    {
        $this->request('DELETE', 'domains', json: $this->withoutNullValues([
            'id' => $id,
            'domain' => $domain,
        ]));

        return EmptyResponseData::from([]);
    }
}
