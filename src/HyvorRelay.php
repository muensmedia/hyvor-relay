<?php

namespace Muensmedia\HyvorRelay;

use Illuminate\Http\Client\Factory;
use Illuminate\Http\Client\PendingRequest;
use Muensmedia\HyvorRelay\Exceptions\HyvorRelayApiException;

class HyvorRelay
{
    public function __construct(
        protected Factory $http
    ) {}

    public function sendEmail(array $payload, ?string $idempotencyKey = null): array
    {
        $headers = [];

        if ($idempotencyKey !== null && $idempotencyKey !== '') {
            $headers['X-Idempotency-Key'] = $idempotencyKey;
        }

        return $this->request('POST', 'sends', json: $payload, headers: $headers);
    }

    public function getSends(array $query = []): array
    {
        return $this->request('GET', 'sends', query: $query);
    }

    public function getSendById(int $id): array
    {
        return $this->request('GET', "sends/{$id}");
    }

    public function getSendByUuid(string $uuid): array
    {
        return $this->request('GET', "sends/uuid/{$uuid}");
    }

    public function getDomains(array $query = []): array
    {
        return $this->request('GET', 'domains', query: $query);
    }

    public function createDomain(string $domain): array
    {
        return $this->request('POST', 'domains', json: ['domain' => $domain]);
    }

    public function verifyDomain(?int $id = null, ?string $domain = null): array
    {
        return $this->request('POST', 'domains/verify', json: $this->withoutNullValues([
            'id' => $id,
            'domain' => $domain,
        ]));
    }

    public function getDomain(?int $id = null, ?string $domain = null): array
    {
        return $this->request('GET', 'domains/by', query: $this->withoutNullValues([
            'id' => $id,
            'domain' => $domain,
        ]));
    }

    public function deleteDomain(?int $id = null, ?string $domain = null): array
    {
        return $this->request('DELETE', 'domains', json: $this->withoutNullValues([
            'id' => $id,
            'domain' => $domain,
        ]));
    }

    public function getWebhooks(): array
    {
        return $this->request('GET', 'webhooks');
    }

    public function createWebhook(string $url, array $events, ?string $description = null): array
    {
        return $this->request('POST', 'webhooks', json: $this->withoutNullValues([
            'url' => $url,
            'events' => $events,
            'description' => $description,
        ]));
    }

    public function updateWebhook(int $id, array $payload): array
    {
        return $this->request('PATCH', "webhooks/{$id}", json: $payload);
    }

    public function deleteWebhook(int $id): array
    {
        return $this->request('DELETE', "webhooks/{$id}");
    }

    public function getWebhookDeliveries(array $query = []): array
    {
        return $this->request('GET', 'webhooks/deliveries', query: $query);
    }

    public function getApiKeys(): array
    {
        return $this->request('GET', 'api-keys');
    }

    public function createApiKey(string $name, array $scopes): array
    {
        return $this->request('POST', 'api-keys', json: [
            'name' => $name,
            'scopes' => $scopes,
        ]);
    }

    public function updateApiKey(int $id, array $payload): array
    {
        return $this->request('PATCH', "api-keys/{$id}", json: $payload);
    }

    public function deleteApiKey(int $id): array
    {
        return $this->request('DELETE', "api-keys/{$id}");
    }

    public function getSuppressions(array $query = []): array
    {
        return $this->request('GET', 'suppressions', query: $query);
    }

    public function deleteSuppression(int $id): array
    {
        return $this->request('DELETE', "suppressions/{$id}");
    }

    public function getAnalyticsStats(?string $period = null): array
    {
        return $this->request('GET', 'analytics/stats', query: $this->withoutNullValues([
            'period' => $period,
        ]));
    }

    public function getAnalyticsSendsChart(): array
    {
        return $this->request('GET', 'analytics/sends/chart');
    }

    protected function request(
        string $method,
        string $uri,
        array $query = [],
        array $json = [],
        array $headers = []
    ): array {
        $response = $this->client($headers)->send(strtoupper($method), ltrim($uri, '/'), [
            'query' => $query,
            'json' => $json,
        ]);

        if (! $response->successful()) {
            throw HyvorRelayApiException::fromResponse($response, strtoupper($method), $uri);
        }

        return $response->json() ?? [];
    }

    protected function client(array $headers = []): PendingRequest
    {
        return $this->http
            ->baseUrl(rtrim((string) config('hyvor-relay.endpoint'), '/').'/api/console')
            ->acceptJson()
            ->asJson()
            ->withToken((string) config('hyvor-relay.api_key'))
            ->timeout((int) config('hyvor-relay.timeout', 10))
            ->connectTimeout((int) config('hyvor-relay.connect_timeout', 5))
            ->withHeaders($headers);
    }

    protected function withoutNullValues(array $data): array
    {
        return array_filter($data, static fn (mixed $value): bool => $value !== null);
    }
}
