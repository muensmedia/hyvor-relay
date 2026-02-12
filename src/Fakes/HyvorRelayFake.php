<?php

namespace Muensmedia\HyvorRelay\Fakes;

use PHPUnit\Framework\Assert;

class HyvorRelayFake
{
    /**
     * @var array<int, array{method: string, arguments: array}>
     */
    protected array $calls = [];

    /**
     * @var array<string, mixed>
     */
    protected array $responses = [];

    public function setResponse(string $method, mixed $response): self
    {
        $this->responses[$method] = $response;

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

        Assert::fail('Expected a matching HyvorRelay facade call but none was recorded.');
    }

    public function assertCalled(string $method, int $times = 1): void
    {
        $actual = collect($this->calls)
            ->filter(fn (array $call): bool => $call['method'] === $method)
            ->count();

        Assert::assertSame($times, $actual, "Expected {$method} to be called {$times} time(s), got {$actual}.");
    }

    public function assertNothingRequested(): void
    {
        Assert::assertCount(0, $this->calls, 'Expected no HyvorRelay facade calls, but at least one was recorded.');
    }

    /**
     * @return array<int, array{method: string, arguments: array}>
     */
    public function calls(): array
    {
        return $this->calls;
    }

    public function __call(string $method, array $arguments): mixed
    {
        $this->calls[] = [
            'method' => $method,
            'arguments' => $arguments,
        ];

        return $this->responses[$method] ?? [];
    }
}
