<?php

namespace Muensmedia\HyvorRelay\Actions\Console\Concerns;

use Muensmedia\HyvorRelay\Exceptions\HyvorRelayApiException;
use Muensmedia\HyvorRelay\Facades\HyvorRelayHttp;

trait InteractsWithConsoleApi
{
    protected function request(
        string $method,
        string $uri,
        array $query = [],
        array $json = [],
        array $headers = [],
        string $apiKeyConfig = 'hyvor-relay.api_keys.general'
    ): array {
        $normalizedMethod = strtoupper($method);
        $normalizedUri = ltrim($uri, '/');

        $response = HyvorRelayHttp::baseUrl(rtrim((string) config('hyvor-relay.endpoint'), '/').'/api/console')
            ->withToken((string) config($apiKeyConfig))
            ->connectTimeout((int) config('hyvor-relay.connect_timeout', 5))
            ->withHeaders($headers)
            ->send($normalizedMethod, $normalizedUri, [
                'query' => $query,
                'json' => $json,
            ]);

        if (! $response->successful()) {
            throw HyvorRelayApiException::fromResponse(
                $response,
                $normalizedMethod,
                $normalizedUri,
                static::class
            );
        }

        return $response->json() ?? [];
    }

    protected function withoutNullValues(array $data): array
    {
        return array_filter($data, static fn (mixed $value): bool => $value !== null);
    }
}
