# Webhooks

This package gives you a complete webhook integration setup:

- a default webhook route
- typed Laravel events for Hyvor webhook payloads (see `docs/webhook-events.md`)
- signature verification via middleware
- signature helper methods via facade

## Package Default Webhook Route

When package routes are enabled, this route is registered by default:

- Method: `POST`
- Path: `/api/hyvor-relay/v1/webhook`
- Route name: `hyvor-relay.api.v1.webhook`
- Middleware: `VerifyWebhookSignature`

Incoming payloads are parsed and mapped to strongly typed DTO-based Laravel events.

## Default Middleware

Use the default package route (already protected), or attach middleware manually:

```php
use Muensmedia\HyvorRelay\Http\Middleware\VerifyWebhookSignature;

Route::post('/webhooks/hyvor', YourController::class)
    ->middleware(VerifyWebhookSignature::class);
```

This reads `hyvor-relay.webhook_secret`.

## Multiple Webhook Routes With Different Secrets

### Use a config key parameter

```php
Route::post('/webhooks/hyvor-marketing', MarketingWebhookController::class)
    ->middleware(VerifyWebhookSignature::class.':config:services.hyvor.marketing_secret');
```

### Use an inline secret parameter

```php
Route::post('/webhooks/hyvor-billing', BillingWebhookController::class)
    ->middleware(VerifyWebhookSignature::class.':your-inline-secret');
```

## Helper Methods

```php
use Muensmedia\HyvorRelay\Facades\HyvorRelay;

$signature = HyvorRelay::signWebhookPayload($rawJsonBody, $secret);
$isValid = HyvorRelay::verifyWebhookSignature($rawJsonBody, $receivedSignature, $secret);
```

## Testing

You can fake helper calls through the facade:

```php
HyvorRelay::fake()->setResponse('verifyWebhookSignature', true);

expect(HyvorRelay::verifyWebhookSignature('{}', 'any'))->toBeTrue();
HyvorRelay::assertCalled('verifyWebhookSignature');
```
