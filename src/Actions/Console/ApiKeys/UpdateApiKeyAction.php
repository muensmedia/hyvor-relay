<?php

namespace Muensmedia\HyvorRelay\Actions\Console\ApiKeys;

use Lorisleiva\Actions\Concerns\AsAction;
use Muensmedia\HyvorRelay\Actions\Console\Concerns\InteractsWithConsoleApi;
use Muensmedia\HyvorRelay\Data\Console\Objects\ApiKeyData;

/**
 * @see https://relay.hyvor.com/docs/console-api#update-api-key
 */
class UpdateApiKeyAction
{
    use AsAction;
    use InteractsWithConsoleApi;

    public function handle(int $id, array $payload): ApiKeyData
    {
        return $this->toData(
            ApiKeyData::class,
            $this->request('PATCH', "api-keys/{$id}", json: $payload)
        );
    }
}
