<div align="center">
<img src="./docs/relay.svg" width="200"  alt="Relay Logo"/>

# Hyvor Relay Laravel Integration<br/>A full Laravel integration for [Hyvor Relay](https://relay.hyvor.com/).

</div>


This package gives you:

- `hyvor-relay` mail transport driver
- typed Console API client via `HyvorRelay` service + facade
- DTO-based responses and webhook payloads via `spatie/laravel-data`
- webhook route + signature validation middleware
- one Laravel event per Hyvor webhook event
- facade fakes/assertions for tests

## Feature Highlights

- Send email through Hyvor Relay transport or Console API
- Manage domains, webhooks, API keys, suppressions, and analytics via facade methods
- Split API keys by use-case: `general`, `send`, `transport`
- Strong typing across requests and responses (DTOs instead of raw arrays)
- Built-in webhook signature helpers: sign + verify
- HTTP preset for package requests (JSON, user-agent, timeout)
- Architecture tests to prevent stray HTTP usage outside `HyvorRelayHttp`

## Documentation

Start here for integration details:

- [docs/environment.md](docs/environment.md) - environment variables and API key fallback behavior
- [docs/webhooks.md](docs/webhooks.md) - webhook route, signature validation, and middleware usage
- [docs/webhook-events.md](docs/webhook-events.md) - all webhook events and Laravel listener patterns
- [docs/queueing.md](docs/queueing.md) - retry, backoff, and idempotency recommendations
- [CONTRIBUTING.md](CONTRIBUTING.md) - contributor workflow, commit schema, setup, tests, linting

## Requirements

- PHP `^8.5`
- Laravel `^12`

## Installation

```bash
composer require muensmedia/hyvor-relay
php artisan vendor:publish --tag=hyvor-relay-config
```

## Configuration

See full env docs here: [docs/environment.md](docs/environment.md)

Minimal setup:

```dotenv
HYVOR_RELAY_ENDPOINT="https://relay.hyvor.com"
HYVOR_RELAY_API_KEY_GENERAL="<your_api_key>"
```

Optional key split:

- `HYVOR_RELAY_API_KEY_SEND` (Console sends endpoints)
- `HYVOR_RELAY_API_KEY_TRANSPORT` (mail transport)

If optional keys are not set, they fallback to `HYVOR_RELAY_API_KEY_GENERAL`.

## Mail Transport Usage

`config/mail.php`:

```php
'default' => env('MAIL_MAILER', 'hyvor'),

'mailers' => [
    'hyvor' => [
        'transport' => 'hyvor-relay',
    ],
],
```

Then send as usual with Laravel Mail.

```php
use Illuminate\Support\Facades\Mail;

Mail::mailer('hyvor')->to('user@example.com')->send(new \App\Mail\WelcomeMail());
```

This is the preferred path if you already use Laravel Mailables.

## Console API Usage (Facade)

Use the facade as the package's public API. Actions are internal implementation details.
Use this when you want direct API access beyond Laravel Mail transport.

```php
use Muensmedia\HyvorRelay\Facades\HyvorRelay;
use Muensmedia\HyvorRelay\Data\Console\Requests\SendEmailPayloadData;

$response = HyvorRelay::sendEmail(SendEmailPayloadData::from([
    'from' => 'app@example.com',
    'to' => 'user@example.com',
    'subject' => 'Welcome',
    'body_text' => 'Hello from Relay',
]), 'welcome-email-123');

$domains = HyvorRelay::getDomains();
$stats = HyvorRelay::getAnalyticsStats('7d');
```

The package exposes facade methods for:

- Sends: send, list, get by ID/UUID
- Domains: list, create, verify, get, delete
- Webhooks: list, create, update, delete, list deliveries
- API keys: list, create, update, delete
- Suppressions: list, delete
- Analytics: stats, sends chart

## Webhooks

Default route:

- `POST /api/hyvor-relay/v1/webhook`
- middleware: `VerifyWebhookSignature`
- unknown events return `204`

For each supported Hyvor webhook event, the package dispatches a typed Laravel event with a DTO payload.

- Webhook setup/signature docs: [docs/webhooks.md](docs/webhooks.md)
- Event map + listener examples: [docs/webhook-events.md](docs/webhook-events.md)

## Queueing Strategy

API calls are synchronous by design. Queueing/retries should be controlled by the consuming app.

Recommended patterns and sample job:

- [docs/queueing.md](docs/queueing.md)

## Testing

Facade fake helpers are included:

```php
use Muensmedia\HyvorRelay\Facades\HyvorRelay;

HyvorRelay::fake()->setResponse('verifyWebhookSignature', true);
HyvorRelay::verifyWebhookSignature('{}', 'sig');
HyvorRelay::assertCalled('verifyWebhookSignature');
```

## Contributing

For local development (Docker and non-Docker), commit schema, tests, and linting commands, see:

- [CONTRIBUTING.md](CONTRIBUTING.md)

## License

MIT
