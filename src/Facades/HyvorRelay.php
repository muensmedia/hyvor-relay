<?php

namespace Muensmedia\HyvorRelay\Facades;

use Illuminate\Support\Facades\Facade;
use Muensmedia\HyvorRelay\Fakes\HyvorRelayFake;

/**
 * @method static array sendEmail(array $payload, ?string $idempotencyKey = null)
 * @method static array getSends(array $query = [])
 * @method static array getSendById(int $id)
 * @method static array getSendByUuid(string $uuid)
 * @method static array getDomains(array $query = [])
 * @method static array createDomain(string $domain)
 * @method static array verifyDomain(?int $id = null, ?string $domain = null)
 * @method static array getDomain(?int $id = null, ?string $domain = null)
 * @method static array deleteDomain(?int $id = null, ?string $domain = null)
 * @method static array getWebhooks()
 * @method static array createWebhook(string $url, array $events, ?string $description = null)
 * @method static array updateWebhook(int $id, array $payload)
 * @method static array deleteWebhook(int $id)
 * @method static array getWebhookDeliveries(array $query = [])
 * @method static array getApiKeys()
 * @method static array createApiKey(string $name, array $scopes)
 * @method static array updateApiKey(int $id, array $payload)
 * @method static array deleteApiKey(int $id)
 * @method static array getSuppressions(array $query = [])
 * @method static array deleteSuppression(int $id)
 * @method static array getAnalyticsStats(?string $period = null)
 * @method static array getAnalyticsSendsChart()
 */
class HyvorRelay extends Facade
{
    public static function fake(): HyvorRelayFake
    {
        $fake = new HyvorRelayFake();

        static::swap($fake);

        return $fake;
    }

    public static function assertRequested(callable $callback): void
    {
        static::getFacadeRoot()->assertRequested($callback);
    }

    public static function assertEndpointRequested(string $facadeMethod, int $times = 1): void
    {
        static::getFacadeRoot()->assertCalled($facadeMethod, $times);
    }

    public static function assertCalled(string $method, int $times = 1): void
    {
        static::getFacadeRoot()->assertCalled($method, $times);
    }

    public static function assertNothingRequested(): void
    {
        static::getFacadeRoot()->assertNothingRequested();
    }

    protected static function getFacadeAccessor(): string
    {
        return \Muensmedia\HyvorRelay\HyvorRelay::class;
    }
}
