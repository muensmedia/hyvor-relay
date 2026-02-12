<?php

namespace Muensmedia\HyvorRelay\Facades;

use Illuminate\Support\Facades\Facade;
use Muensmedia\HyvorRelay\Fakes\HyvorRelayFake;

/**
 * Static facade proxy for the HyvorRelay service.
 *
 * @method static \Muensmedia\HyvorRelay\Data\Console\Responses\SendEmailResponseData sendEmail(array $payload, ?string $idempotencyKey = null)
 * @method static \Spatie\LaravelData\DataCollection<int, \Muensmedia\HyvorRelay\Data\Console\Objects\SendData> getSends(array $query = [])
 * @method static \Muensmedia\HyvorRelay\Data\Console\Objects\SendData getSendById(int $id)
 * @method static \Muensmedia\HyvorRelay\Data\Console\Objects\SendData getSendByUuid(string $uuid)
 * @method static \Spatie\LaravelData\DataCollection<int, \Muensmedia\HyvorRelay\Data\Console\Objects\DomainData> getDomains(array $query = [])
 * @method static \Muensmedia\HyvorRelay\Data\Console\Objects\DomainData createDomain(string $domain)
 * @method static \Muensmedia\HyvorRelay\Data\Console\Objects\DomainData verifyDomain(?int $id = null, ?string $domain = null)
 * @method static \Muensmedia\HyvorRelay\Data\Console\Objects\DomainData getDomain(?int $id = null, ?string $domain = null)
 * @method static \Muensmedia\HyvorRelay\Data\Console\Responses\EmptyResponseData deleteDomain(?int $id = null, ?string $domain = null)
 * @method static \Spatie\LaravelData\DataCollection<int, \Muensmedia\HyvorRelay\Data\Console\Objects\WebhookData> getWebhooks()
 * @method static \Muensmedia\HyvorRelay\Data\Console\Objects\WebhookData createWebhook(string $url, array $events, ?string $description = null)
 * @method static \Muensmedia\HyvorRelay\Data\Console\Objects\WebhookData updateWebhook(int $id, array $payload)
 * @method static \Muensmedia\HyvorRelay\Data\Console\Responses\EmptyResponseData deleteWebhook(int $id)
 * @method static \Spatie\LaravelData\DataCollection<int, \Muensmedia\HyvorRelay\Data\Console\Objects\WebhookDeliveryData> getWebhookDeliveries(array $query = [])
 * @method static \Spatie\LaravelData\DataCollection<int, \Muensmedia\HyvorRelay\Data\Console\Objects\ApiKeyData> getApiKeys()
 * @method static \Muensmedia\HyvorRelay\Data\Console\Objects\ApiKeyData createApiKey(string $name, array $scopes)
 * @method static \Muensmedia\HyvorRelay\Data\Console\Objects\ApiKeyData updateApiKey(int $id, array $payload)
 * @method static \Muensmedia\HyvorRelay\Data\Console\Responses\EmptyResponseData deleteApiKey(int $id)
 * @method static \Spatie\LaravelData\DataCollection<int, \Muensmedia\HyvorRelay\Data\Console\Objects\SuppressionData> getSuppressions(array $query = [])
 * @method static \Muensmedia\HyvorRelay\Data\Console\Responses\EmptyResponseData deleteSuppression(int $id)
 * @method static \Muensmedia\HyvorRelay\Data\Console\Responses\AnalyticsStatsData getAnalyticsStats(?string $period = null)
 * @method static \Muensmedia\HyvorRelay\Data\Console\Responses\AnalyticsSendsChartData getAnalyticsSendsChart()
 * @method static string signWebhookPayload(string $rawBody, ?string $secret = null)
 * @method static bool verifyWebhookSignature(string $rawBody, string $signature, ?string $secret = null)
 */
class HyvorRelay extends Facade
{
    /**
     * Replace the facade root with an in-memory fake for testing.
     */
    public static function fake(): HyvorRelayFake
    {
        $fake = new HyvorRelayFake();

        static::swap($fake);

        return $fake;
    }

    /**
     * Assert at least one recorded call matches the callback constraints.
     */
    public static function assertRequested(callable $callback): void
    {
        static::getFacadeRoot()->assertRequested($callback);
    }

    /**
     * Assert a facade method was called a given number of times.
     */
    public static function assertEndpointRequested(string $facadeMethod, int $times = 1): void
    {
        static::getFacadeRoot()->assertCalled($facadeMethod, $times);
    }

    /**
     * Assert a facade method was called a given number of times.
     */
    public static function assertCalled(string $method, int $times = 1): void
    {
        static::getFacadeRoot()->assertCalled($method, $times);
    }

    /**
     * Assert no calls were recorded on the fake facade.
     */
    public static function assertNothingRequested(): void
    {
        static::getFacadeRoot()->assertNothingRequested();
    }

    /**
     * Resolve the container binding used by this facade.
     */
    protected static function getFacadeAccessor(): string
    {
        return \Muensmedia\HyvorRelay\HyvorRelay::class;
    }
}
