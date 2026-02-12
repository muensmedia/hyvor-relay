# Webhook Signature Verification

This package provides two ways to verify Hyvor Relay webhook signatures:

- a middleware: `VerifyWebhookSignature`
- helper methods via facade: `HyvorRelay::signWebhookPayload(...)` and `HyvorRelay::verifyWebhookSignature(...)`

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
