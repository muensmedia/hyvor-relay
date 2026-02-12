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

class HyvorRelay
{
    public function sendEmail(array $payload, ?string $idempotencyKey = null): array
    {
        return SendEmailAction::run($payload, $idempotencyKey);
    }

    public function getSends(array $query = []): array
    {
        return GetSendsAction::run($query);
    }

    public function getSendById(int $id): array
    {
        return GetSendByIdAction::run($id);
    }

    public function getSendByUuid(string $uuid): array
    {
        return GetSendByUuidAction::run($uuid);
    }

    public function getDomains(array $query = []): array
    {
        return GetDomainsAction::run($query);
    }

    public function createDomain(string $domain): array
    {
        return CreateDomainAction::run($domain);
    }

    public function verifyDomain(?int $id = null, ?string $domain = null): array
    {
        return VerifyDomainAction::run($id, $domain);
    }

    public function getDomain(?int $id = null, ?string $domain = null): array
    {
        return GetDomainAction::run($id, $domain);
    }

    public function deleteDomain(?int $id = null, ?string $domain = null): array
    {
        return DeleteDomainAction::run($id, $domain);
    }

    public function getWebhooks(): array
    {
        return GetWebhooksAction::run();
    }

    public function createWebhook(string $url, array $events, ?string $description = null): array
    {
        return CreateWebhookAction::run($url, $events, $description);
    }

    public function updateWebhook(int $id, array $payload): array
    {
        return UpdateWebhookAction::run($id, $payload);
    }

    public function deleteWebhook(int $id): array
    {
        return DeleteWebhookAction::run($id);
    }

    public function getWebhookDeliveries(array $query = []): array
    {
        return GetWebhookDeliveriesAction::run($query);
    }

    public function getApiKeys(): array
    {
        return GetApiKeysAction::run();
    }

    public function createApiKey(string $name, array $scopes): array
    {
        return CreateApiKeyAction::run($name, $scopes);
    }

    public function updateApiKey(int $id, array $payload): array
    {
        return UpdateApiKeyAction::run($id, $payload);
    }

    public function deleteApiKey(int $id): array
    {
        return DeleteApiKeyAction::run($id);
    }

    public function getSuppressions(array $query = []): array
    {
        return GetSuppressionsAction::run($query);
    }

    public function deleteSuppression(int $id): array
    {
        return DeleteSuppressionAction::run($id);
    }

    public function getAnalyticsStats(?string $period = null): array
    {
        return GetAnalyticsStatsAction::run($period);
    }

    public function getAnalyticsSendsChart(): array
    {
        return GetAnalyticsSendsChartAction::run();
    }
}
