<?php

namespace Muensmedia\HyvorRelay\Actions\Console\ApiKeys;

use Lorisleiva\Actions\Concerns\AsAction;
use Muensmedia\HyvorRelay\Actions\Console\Concerns\InteractsWithConsoleApi;
use Muensmedia\HyvorRelay\Data\Console\Objects\ApiKeyData;

/**
 * @see https://relay.hyvor.com/docs/console-api#create-api-key
 */
class CreateApiKeyAction
{
    use AsAction;
    use InteractsWithConsoleApi;

    public function handle(string $name, array $scopes): ApiKeyData
    {
        return $this->toData(
            ApiKeyData::class,
            $this->request('POST', 'api-keys', json: [
                'name' => $name,
                'scopes' => $scopes,
            ])
        );
    }
}
