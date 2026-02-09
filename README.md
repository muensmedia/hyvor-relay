# 📨 Hyvor Relay (Laravel Mail Transport)

Laravel package that adds a custom mail transport (`hyvor-relay`) to send emails through a Hyvor Relay endpoint (Hyvor-hosted or self-hosted).

## 🔎 Contents

- What You Get
- Requirements
- Installation (Private GitLab / muensmedia)
- Configuration
- Usage
- How The Transport Works
- Quick Test Mailable (Optional)
- Local Development (This Repo)
- Troubleshooting

## ✨ What You Get

- A Laravel mail transport driver: `hyvor-relay`
- Simple `.env` configuration (`HYVOR_RELAY_API_KEY`, `HYVOR_RELAY_ENDPOINT`)
- Set Hyvor Relay as the default mailer, or keep your default (SMTP, SES, etc.) and use Hyvor only for specific mails

## ✅ Requirements

- PHP `^8.5`
- Laravel `^12.50`
- A Hyvor Relay API key and endpoint

## 📦 Installation (Private GitLab / muensmedia)

This package is hosted on a private GitLab, so Composer must be able to authenticate and must know the repository URL.

1. Add a GitLab token (this writes to Composer `auth.json`):

```bash
composer config gitlab-token.git.muensmedia.de <YOUR_GITLAB_TOKEN>
```

Token requirements (typical):

- Personal Access Token: `read_api` or `read_package_registry`
- Alternatively: GitLab Deploy Token with `read_package_registry`

2. Tell Composer that `git.muensmedia.de` is a GitLab domain:

```bash
composer config gitlab-domains "git.muensmedia.de"
```

3. Add the Composer repository for this project:

```bash
composer config repositories.muensmedia-hyvor-relay composer https://git.muensmedia.de/api/v4/projects/mm%2Fhyvor-relay-laravel/packages/composer/packages.json
```

4. Require the package:

```bash
composer require muensmedia/hyvor-relay
```

5. Publish the config (optional, recommended):

```bash
php artisan vendor:publish --tag=hyvor-relay-config
```

Notes:

- Prefer setting the token globally if you use multiple private packages:

```bash
composer config --global gitlab-token.git.muensmedia.de <YOUR_GITLAB_TOKEN>
composer config --global gitlab-domains "git.muensmedia.de"
```

- Avoid committing real tokens. If you commit an `auth.json`, treat it as a secret.

## ⚙️ Configuration

Set the API key and endpoint in your Laravel app `.env`:

```dotenv
HYVOR_RELAY_API_KEY="your-api-key"
HYVOR_RELAY_ENDPOINT="https://relay.hyvor.com"
```

Notes:

- `HYVOR_RELAY_ENDPOINT` must be the base URL (no trailing `/api/...` path). The transport will call `POST {endpoint}/api/console/sends`.
- For a self-hosted Relay instance, set `HYVOR_RELAY_ENDPOINT` to your server URL.

## 📨 Usage

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

## 🧠 How The Transport Works

Implementation details (high level):

- Laravel calls Symfony Mailer with the configured transport.
- This package registers the `hyvor-relay` transport and maps it to a Symfony transport with the scheme `hyvor+api`.
- Sending an email performs an HTTP request: `POST {HYVOR_RELAY_ENDPOINT}/api/console/sends`
- Authentication is sent via header: `Authorization: Bearer {HYVOR_RELAY_API_KEY}`
- The JSON payload is built from the `Email` object (simplified):

```json
{
  "from": { "name": "Sender", "email": "sender@example.com" },
  "to": [{ "name": "User", "email": "user@example.com" }],
  "cc": [],
  "bcc": [],
  "subject": "Subject",
  "body_html": "<p>Hello</p>",
  "body_text": "Hello",
  "headers": {
    "Reply-To": "reply@example.com"
  },
  "attachments": [
    { "name": "file.pdf", "content": "BASE64..." }
  ]
}
```

- Any non-2xx response becomes a transport exception.

## 🧩 Quick Test Mailable (Optional)

This package includes `Muensmedia\HyvorRelay\Mailable\HyvorMailable` as a convenience for sending raw HTML.

```php
use Illuminate\Support\Facades\Mail;
use Muensmedia\HyvorRelay\Mailable\HyvorMailable;

Mail::to('user@example.com')->send(
    new HyvorMailable('<h1>Hello from Hyvor Relay</h1>')
);
```

## 🧪 Local Development (This Repo)

This repository includes a minimal Docker setup (PHP 8.5 container) and helper scripts in `tools/`.

Requirements:

- Docker + Docker Compose

Setup:

```bash
cp .env.example .env
docker compose up -d
./tools/composer install
```

Run tests:

```bash
./tools/php ./vendor/bin/pest
./tools/php ./vendor/bin/pest --compact --profile
```

Optional coverage (requires Xdebug or PCOV inside the PHP runtime):

```bash
./tools/php ./vendor/bin/pest --coverage --configuration phpunit.coverage.xml.dist
```

## 🩺 Troubleshooting

`UnsupportedSchemeException`:

- Ensure your mailer uses `transport => 'hyvor-relay'`.

401/403 from Relay:

- Check `HYVOR_RELAY_API_KEY`.

Connection issues:

- Check `HYVOR_RELAY_ENDPOINT` and that your app can reach it (DNS, firewall, TLS).
