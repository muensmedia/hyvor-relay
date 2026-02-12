<?php

namespace Muensmedia\HyvorRelay\Actions\Console\Concerns;

use Muensmedia\HyvorRelay\Exceptions\HyvorRelayApiException;
use Muensmedia\HyvorRelay\Facades\HyvorRelayHttp;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

trait InteractsWithConsoleApi
{
    protected function request(
        string $method,
        string $uri,
        array $query = [],
        array $json = [],
        array $headers = []
    ): array {
        $normalizedMethod = strtoupper($method);
        $normalizedUri = ltrim($uri, '/');

        $response = HyvorRelayHttp::baseUrl(rtrim((string) config('hyvor-relay.endpoint'), '/').'/api/console')
            ->withToken((string) config('hyvor-relay.api_key'))
            ->connectTimeout((int) config('hyvor-relay.connect_timeout', 5))
            ->withHeaders($headers)
            ->send($normalizedMethod, $normalizedUri, [
                'query' => $query,
                'json' => $json,
            ]);

        if (! $response->successful()) {
            throw HyvorRelayApiException::fromResponse($response, $normalizedMethod, $normalizedUri);
        }

        return $response->json() ?? [];
    }

    protected function withoutNullValues(array $data): array
    {
        return array_filter($data, static fn (mixed $value): bool => $value !== null);
    }

    /**
     * @template TData of Data
     *
     * @param  class-string<TData>  $class
     * @return TData
     */
    protected function toData(string $class, array $payload): Data
    {
        return $class::from($payload);
    }

    /**
     * @template TData of Data
     *
     * @param  class-string<TData>  $class
     */
    protected function toCollection(string $class, array $payload): DataCollection
    {
        return $class::collect($payload, DataCollection::class);
    }
}
