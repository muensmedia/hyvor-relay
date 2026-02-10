# Hyvor Relay (Laravel Mail Transport)

Laravel package that adds a custom mail transport (`hyvor-relay`) to send emails through a Hyvor Relay endpoint (Hyvor-hosted or self-hosted).

__[TOC]__

## What You Get

- A Laravel mail transport driver: `hyvor-relay`
- Simple `.env` configuration (`HYVOR_RELAY_API_KEY`, `HYVOR_RELAY_ENDPOINT`)
- Set Hyvor Relay as the default mailer, or keep your default (SMTP, SES, etc.) and use Hyvor only for specific mails

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

Set the API key and endpoint in your Laravel app `.env`:

```dotenv
HYVOR_RELAY_API_KEY="your-api-key"
HYVOR_RELAY_ENDPOINT="https://relay.hyvor.com"
```

Notes:

- `HYVOR_RELAY_ENDPOINT` must be the base URL (no trailing `/api/...` path). The transport will call `POST {endpoint}/api/console/sends`.
- For a self-hosted Relay instance, set `HYVOR_RELAY_ENDPOINT` to your server URL.

## Usage

The package registers a mail transport driver named `hyvor-relay`. To use it, configure a Laravel mailer that uses this transport.

### Option A: Use Hyvor Relay As The Default Mailer

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

Now any Laravel mail send will go through Hyvor Relay:

```php
use Illuminate\Support\Facades\Mail;

Mail::to('user@example.com')->send(new \App\Mail\WelcomeMail());
```

### Option B: Keep Your Default Mailer, Use Hyvor Only Where Needed

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

## Local Development (This Repo)

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

Optional coverage (requires Xdebug or PCOV inside the PHP runtime):

```bash
docker compose exec php php ./vendor/bin/pest --coverage --configuration phpunit.coverage.xml.dist
```

## Troubleshooting

`UnsupportedSchemeException`:

- Ensure your mailer uses `transport => 'hyvor-relay'`.

401/403 from Relay:

- Check `HYVOR_RELAY_API_KEY`.

Connection issues:

- Check `HYVOR_RELAY_ENDPOINT` and that your app can reach it (DNS, firewall, TLS).
