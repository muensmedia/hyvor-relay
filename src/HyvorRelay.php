<?php

namespace Muensmedia\HyvorRelay;

use Muensmedia\HyvorRelay\Actions\Console\Analytics\GetAnalyticsSendsChartAction;
use Muensmedia\HyvorRelay\Actions\Console\Analytics\GetAnalyticsStatsAction;
use Muensmedia\HyvorRelay\Actions\Console\ApiKeys\CreateApiKeyAction;
use Muensmedia\HyvorRelay\Actions\Console\ApiKeys\DeleteApiKeyAction;
use Muensmedia\HyvorRelay\Actions\Console\ApiKeys\GetApiKeysAction;
use Muensmedia\HyvorRelay\Actions\Console\ApiKeys\UpdateApiKeyAction;
use Muensmedia\HyvorRelay\Actions\Console\Domains\CreateDomainAction;
use Muensmedia\HyvorRelay\Actions\Console\Domains\DeleteDomainAction;
use Muensmedia\HyvorRelay\Actions\Console\Domains\GetDomainAction;
use Muensmedia\HyvorRelay\Actions\Console\Domains\GetDomainsAction;
use Muensmedia\HyvorRelay\Actions\Console\Domains\VerifyDomainAction;
use Muensmedia\HyvorRelay\Actions\Console\Sends\GetSendByIdAction;
use Muensmedia\HyvorRelay\Actions\Console\Sends\GetSendByUuidAction;
use Muensmedia\HyvorRelay\Actions\Console\Sends\GetSendsAction;
use Muensmedia\HyvorRelay\Actions\Console\Sends\SendEmailAction;
use Muensmedia\HyvorRelay\Actions\Console\Suppressions\DeleteSuppressionAction;
use Muensmedia\HyvorRelay\Actions\Console\Suppressions\GetSuppressionsAction;
use Muensmedia\HyvorRelay\Actions\Console\Webhooks\CreateWebhookAction;
use Muensmedia\HyvorRelay\Actions\Console\Webhooks\DeleteWebhookAction;
use Muensmedia\HyvorRelay\Actions\Console\Webhooks\GetWebhookDeliveriesAction;
use Muensmedia\HyvorRelay\Actions\Console\Webhooks\GetWebhooksAction;
use Muensmedia\HyvorRelay\Actions\Console\Webhooks\UpdateWebhookAction;
use Muensmedia\HyvorRelay\Data\Console\Objects\ApiKeyData;
use Muensmedia\HyvorRelay\Data\Console\Objects\DomainData;
use Muensmedia\HyvorRelay\Data\Console\Objects\SendData;
use Muensmedia\HyvorRelay\Data\Console\Objects\WebhookData;
use Muensmedia\HyvorRelay\Data\Console\Responses\AnalyticsSendsChartData;
use Muensmedia\HyvorRelay\Data\Console\Responses\AnalyticsStatsData;
use Muensmedia\HyvorRelay\Data\Console\Responses\EmptyResponseData;
use Muensmedia\HyvorRelay\Data\Console\Responses\SendEmailResponseData;
use Spatie\LaravelData\DataCollection;

class HyvorRelay
{
    /**
     * Queue an email via the Console API.
     */
    public function sendEmail(array $payload, ?string $idempotencyKey = null): SendEmailResponseData
    {
        return SendEmailAction::run($payload, $idempotencyKey);
    }

    /**
     * List sends with optional filtering and pagination parameters.
     */
    public function getSends(array $query = []): DataCollection
    {
        return GetSendsAction::run($query);
    }

    /**
     * Fetch a single send by numeric ID.
     */
    public function getSendById(int $id): SendData
    {
        return GetSendByIdAction::run($id);
    }

    /**
     * Fetch a single send by UUID.
     */
    public function getSendByUuid(string $uuid): SendData
    {
        return GetSendByUuidAction::run($uuid);
    }

    /**
     * List domains for the current project.
     */
    public function getDomains(array $query = []): DataCollection
    {
        return GetDomainsAction::run($query);
    }

    /**
     * Create a new sending domain.
     */
    public function createDomain(string $domain): DomainData
    {
        return CreateDomainAction::run($domain);
    }

    /**
     * Trigger domain verification by ID or domain name.
     */
    public function verifyDomain(?int $id = null, ?string $domain = null): DomainData
    {
        return VerifyDomainAction::run($id, $domain);
    }

    /**
     * Fetch a domain by ID or domain name.
     */
    public function getDomain(?int $id = null, ?string $domain = null): DomainData
    {
        return GetDomainAction::run($id, $domain);
    }

    /**
     * Delete a domain by ID or domain name.
     */
    public function deleteDomain(?int $id = null, ?string $domain = null): EmptyResponseData
    {
        return DeleteDomainAction::run($id, $domain);
    }

    /**
     * List configured webhooks.
     */
    public function getWebhooks(): DataCollection
    {
        return GetWebhooksAction::run();
    }

    /**
     * Create a new webhook endpoint configuration.
     */
    public function createWebhook(string $url, array $events, ?string $description = null): WebhookData
    {
        return CreateWebhookAction::run($url, $events, $description);
    }

    /**
     * Update an existing webhook configuration.
     */
    public function updateWebhook(int $id, array $payload): WebhookData
    {
        return UpdateWebhookAction::run($id, $payload);
    }

    /**
     * Delete a webhook by ID.
     */
    public function deleteWebhook(int $id): EmptyResponseData
    {
        return DeleteWebhookAction::run($id);
    }

    /**
     * List webhook delivery attempts.
     */
    public function getWebhookDeliveries(array $query = []): DataCollection
    {
        return GetWebhookDeliveriesAction::run($query);
    }

    /**
     * List API keys in the current project.
     */
    public function getApiKeys(): DataCollection
    {
        return GetApiKeysAction::run();
    }

    /**
     * Create a new API key with scopes.
     */
    public function createApiKey(string $name, array $scopes): ApiKeyData
    {
        return CreateApiKeyAction::run($name, $scopes);
    }

    /**
     * Update an API key by ID.
     */
    public function updateApiKey(int $id, array $payload): ApiKeyData
    {
        return UpdateApiKeyAction::run($id, $payload);
    }

    /**
     * Delete an API key by ID.
     */
    public function deleteApiKey(int $id): EmptyResponseData
    {
        return DeleteApiKeyAction::run($id);
    }

    /**
     * List suppression entries with optional filters.
     */
    public function getSuppressions(array $query = []): DataCollection
    {
        return GetSuppressionsAction::run($query);
    }

    /**
     * Delete a suppression entry by ID.
     */
    public function deleteSuppression(int $id): EmptyResponseData
    {
        return DeleteSuppressionAction::run($id);
    }

    /**
     * Fetch aggregate analytics statistics.
     */
    public function getAnalyticsStats(?string $period = null): AnalyticsStatsData
    {
        return GetAnalyticsStatsAction::run($period);
    }

    /**
     * Fetch analytics chart data for sends over time.
     */
    public function getAnalyticsSendsChart(): AnalyticsSendsChartData
    {
        return GetAnalyticsSendsChartAction::run();
    }
}
