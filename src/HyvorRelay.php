<?php

namespace Muensmedia\HyvorRelay;

use Illuminate\Support\Str;
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
use Muensmedia\HyvorRelay\Data\Console\Requests\SendEmailPayloadData;
use Muensmedia\HyvorRelay\Data\Console\Responses\AnalyticsSendsChartData;
use Muensmedia\HyvorRelay\Data\Console\Responses\AnalyticsStatsData;
use Muensmedia\HyvorRelay\Data\Console\Responses\EmptyResponseData;
use Muensmedia\HyvorRelay\Data\Console\Responses\SendEmailResponseData;
use Spatie\LaravelData\DataCollection;

class HyvorRelay
{
    /**
     * Send an email via the Console API.
     */
    public function sendEmail(SendEmailPayloadData $payload, ?string $idempotencyKey = null): SendEmailResponseData
    {
        return SendEmailAction::run($payload, $idempotencyKey);
    }

    /**
     * List sends with optional filtering and pagination parameters.
     *
     * @return DataCollection<int, SendData>
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
     *
     * @return DataCollection<int, DomainData>
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
     *
     * @return DataCollection<int, WebhookData>
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
     *
     * @return DataCollection<int, \Muensmedia\HyvorRelay\Data\Console\Objects\WebhookDeliveryData>
     */
    public function getWebhookDeliveries(array $query = []): DataCollection
    {
        return GetWebhookDeliveriesAction::run($query);
    }

    /**
     * List API keys in the current project.
     *
     * @return DataCollection<int, ApiKeyData>
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
     *
     * @return DataCollection<int, \Muensmedia\HyvorRelay\Data\Console\Objects\SuppressionData>
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

    /**
     * Create a HMAC-SHA256 signature for a raw webhook JSON payload.
     */
    public function signWebhookPayload(string $rawBody, ?string $secret = null): string
    {
        $resolvedSecret = $secret ?? (string) config('hyvor-relay.webhook_secret');

        return hash_hmac('sha256', $rawBody, $resolvedSecret);
    }

    /**
     * Verify an incoming webhook signature against the raw payload.
     */
    public function verifyWebhookSignature(string $rawBody, string $signature, ?string $secret = null): bool
    {
        $normalizedSignature = trim($signature);

        if (Str::startsWith($normalizedSignature, 'sha256=')) {
            $normalizedSignature = substr($normalizedSignature, 7);
        }

        if ($normalizedSignature === '') {
            return false;
        }

        return hash_equals(
            $this->signWebhookPayload($rawBody, $secret),
            $normalizedSignature
        );
    }
}
