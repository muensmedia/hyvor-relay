<?php

namespace Muensmedia\HyvorRelay\Actions\Console\ApiKeys;

use Lorisleiva\Actions\Concerns\AsObject;
use Muensmedia\HyvorRelay\Actions\Console\Concerns\InteractsWithConsoleApi;
use Muensmedia\HyvorRelay\Data\Console\Objects\ApiKeyData;

/**
 * @see https://relay.hyvor.com/docs/console-api#create-api-key
 */
class CreateApiKeyAction
{
    use AsObject, InteractsWithConsoleApi;

    public function handle(string $name, array $scopes): ApiKeyData
    {
        return ApiKeyData::from(
            $this->request('POST', 'api-keys', json: [
                'name' => $name,
                'scopes' => $scopes,
            ])
        );
    }
}
