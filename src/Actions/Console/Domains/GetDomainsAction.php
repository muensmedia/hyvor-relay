<?php

namespace Muensmedia\HyvorRelay\Actions\Console\Domains;

use Lorisleiva\Actions\Concerns\AsObject;
use Muensmedia\HyvorRelay\Actions\Console\Concerns\InteractsWithConsoleApi;
use Muensmedia\HyvorRelay\Data\Console\Objects\DomainData;
use Spatie\LaravelData\DataCollection;

/**
 * @see https://relay.hyvor.com/docs/api-console#get-domains
 */
class GetDomainsAction
{
    use AsObject, InteractsWithConsoleApi;

    /**
     * @return DataCollection<int, DomainData>
     */
    public function handle(array $query = []): DataCollection
    {
        return DomainData::collect(
            $this->request('GET', 'domains', query: $query),
            DataCollection::class
        );
    }
}
