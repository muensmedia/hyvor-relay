<?php

namespace Muensmedia\HyvorRelay\Actions\Console\Domains;

use Lorisleiva\Actions\Concerns\AsAction;
use Muensmedia\HyvorRelay\Actions\Console\Concerns\InteractsWithConsoleApi;
use Muensmedia\HyvorRelay\Data\Console\Objects\DomainData;
use Spatie\LaravelData\DataCollection;

/**
 * @see https://relay.hyvor.com/docs/console-api#get-domains
 */
class GetDomainsAction
{
    use AsAction;
    use InteractsWithConsoleApi;

    public function handle(array $query = []): DataCollection
    {
        return $this->toCollection(
            DomainData::class,
            $this->request('GET', 'domains', query: $query)
        );
    }
}
