<?php

namespace Muensmedia\HyvorRelay\Actions\Console\ApiKeys;

use Lorisleiva\Actions\Concerns\AsObject;
use Muensmedia\HyvorRelay\Actions\Console\Concerns\InteractsWithConsoleApi;
use Muensmedia\HyvorRelay\Data\Console\Objects\ApiKeyData;

/**
 * @see https://relay.hyvor.com/docs/console-api#update-api-key
 */
class UpdateApiKeyAction
{
    use AsObject, InteractsWithConsoleApi;

    public function handle(int $id, array $payload): ApiKeyData
    {
        return ApiKeyData::from(
            $this->request('PATCH', "api-keys/{$id}", json: $payload)
        );
    }
}
