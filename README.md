# Hyvor Relay (Laravel mail transport)

A Laravel package that adds a custom mail transport (`hyvor-relay`) to send emails through a Hyvor Relay endpoint (Hyvor-hosted or self-hosted).

__[TOC]__

## What you get

- A Laravel mail transport driver: `hyvor-relay`
- Environment variable reference: [docs/environment.md](docs/environment.md)
- Set Hyvor Relay as the default mailer, or keep your default (SMTP, SES, etc.) and use Hyvor only for specific emails
- Webhook event reference + Laravel listener examples: [docs/webhook-events.md](docs/webhook-events.md)
- Queue/retry best practices for Console API usage: [docs/queueing.md](docs/queueing.md)

## Requirements

- PHP `^8.5`
- Laravel `^12`
- A Hyvor Relay API key and endpoint

## Installation (Composer / Packagist)

```bash
composer require muensmedia/hyvor-relay
```

Publish the config (optional, recommended):

```bash
php artisan vendor:publish --tag=hyvor-relay-config
```

## Configuration

See [docs/environment.md](docs/environment.md) for all required and optional `.env` variables.

## Usage

The package registers a mail transport driver named `hyvor-relay`. To use it, configure a Laravel mailer that uses this transport.

### Option A: use Hyvor Relay as the default mailer

`config/mail.php`:

```php
'default' => env('MAIL_MAILER', 'hyvor'),

'mailers' => [
    'hyvor' => [
        'transport' => 'hyvor-relay',
    ],
],
```

`.env`:

```dotenv
MAIL_MAILER=hyvor
```

Now any mail sent by Laravel will go through Hyvor Relay:

```php
use Illuminate\Support\Facades\Mail;

Mail::to('user@example.com')->send(new \App\Mail\WelcomeMail());
```

### Option B: keep your default mailer, use Hyvor only where needed

If your app default mailer is something else (for example `smtp`), you can still keep a dedicated `hyvor` mailer and use it only for specific emails.

Example `config/mail.php`:

```php
'default' => env('MAIL_MAILER', 'smtp'),

'mailers' => [
    'smtp' => [
        'transport' => 'smtp',
        'host' => env('MAIL_HOST'),
        'port' => env('MAIL_PORT', 587),
        'encryption' => env('MAIL_ENCRYPTION', 'tls'),
        'username' => env('MAIL_USERNAME'),
        'password' => env('MAIL_PASSWORD'),
        'timeout' => null,
        'local_domain' => env('MAIL_EHLO_DOMAIN'),
    ],

    'hyvor' => [
        'transport' => 'hyvor-relay',
    ],
],
```

Then send via Hyvor Relay explicitly:

```php
use Illuminate\Support\Facades\Mail;

Mail::mailer('hyvor')
    ->to('user@example.com')
    ->send(new \App\Mail\WelcomeMail());
```

### Idempotency (recommended for retries)

Hyvor Relay supports idempotency via the `X-Idempotency-Key` HTTP header. When you retry a send request with the same key, Relay can short-circuit and return the original response instead of queueing a duplicate email.

In Laravel you can set this header on the underlying Symfony message:

```php
Mail::to('user@example.com')->send(
    (new \App\Mail\WelcomeMail())
        ->withSymfonyMessage(function (\Symfony\Component\Mime\Email $message) use ($userId) {
            $message->getHeaders()->addTextHeader('X-Idempotency-Key', "welcome-email-{$userId}");
        })
);
```

## Local development (this repo)

This repository includes a minimal Docker setup (PHP 8.5 container).

Requirements:

- Docker + Docker Compose

Setup:

```bash
cp .env.example .env
docker compose up -d
docker compose exec php composer install
```

Run tests:

```bash
docker compose exec php php ./vendor/bin/pest
docker compose exec php php ./vendor/bin/pest --compact --profile
```

Run Pint (code style):

```bash
docker compose exec php php ./vendor/bin/pint
```

Check formatting only (used in CI, no changes will be applied):

```bash
docker compose exec php php ./vendor/bin/pint --test
```

Optional coverage (requires Xdebug or PCOV inside the PHP runtime):

```bash
docker compose exec php php ./vendor/bin/pest --coverage --configuration phpunit.coverage.xml.dist
```

## Troubleshooting

`UnsupportedSchemeException`:

- Ensure your mailer uses `transport => 'hyvor-relay'`.

401/403 from Relay:

- Check `HYVOR_RELAY_API_KEY_GENERAL` (and `HYVOR_RELAY_API_KEY_SEND` / `HYVOR_RELAY_API_KEY_TRANSPORT` if you use split keys).

Connection issues:

- Check `HYVOR_RELAY_ENDPOINT` and that your app can reach it (DNS, firewall, TLS).
