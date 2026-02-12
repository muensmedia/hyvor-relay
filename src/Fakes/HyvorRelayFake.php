<?php

namespace Muensmedia\HyvorRelay\Fakes;

use Illuminate\Http\Client\Factory;
use Muensmedia\HyvorRelay\HyvorRelay;
use PHPUnit\Framework\Assert;

class HyvorRelayFake extends HyvorRelay
{
    /**
     * @var array<int, array{method: string, uri: string, query: array, json: array, headers: array}>
     */
    protected array $calls = [];

    /**
     * @var array<string, array>
     */
    protected array $responses = [];

    public function __construct(?Factory $http = null)
    {
        parent::__construct($http ?? new Factory());
    }

    public function setResponse(string $method, string $uri, array $response): self
    {
        $this->responses[$this->responseKey($method, $uri)] = $response;

        return $this;
    }

    public function assertRequested(callable $callback): void
    {
        foreach ($this->calls as $call) {
            if ($callback($call) === true) {
                Assert::assertTrue(true);

                return;
            }
        }

        Assert::fail('Expected a matching Hyvor Relay API request but none was recorded.');
    }

    public function assertEndpointRequested(string $method, string $uri, int $times = 1): void
    {
        $actual = collect($this->calls)
            ->filter(fn (array $call): bool => $call['method'] === strtoupper($method) && $call['uri'] === ltrim($uri, '/'))
            ->count();

        Assert::assertSame(
            $times,
            $actual,
            "Expected {$method} {$uri} to be called {$times} time(s), got {$actual}."
        );
    }

    public function assertNothingRequested(): void
    {
        Assert::assertCount(0, $this->calls, 'Expected no Hyvor Relay API request, but at least one was recorded.');
    }

    /**
     * @return array<int, array{method: string, uri: string, query: array, json: array, headers: array}>
     */
    public function calls(): array
    {
        return $this->calls;
    }

    protected function request(
        string $method,
        string $uri,
        array $query = [],
        array $json = [],
        array $headers = []
    ): array {
        $normalizedMethod = strtoupper($method);
        $normalizedUri = ltrim($uri, '/');

        $this->calls[] = [
            'method' => $normalizedMethod,
            'uri' => $normalizedUri,
            'query' => $query,
            'json' => $json,
            'headers' => $headers,
        ];

        return $this->responses[$this->responseKey($normalizedMethod, $normalizedUri)] ?? [];
    }

    protected function responseKey(string $method, string $uri): string
    {
        return strtoupper($method).' '.ltrim($uri, '/');
    }
}
