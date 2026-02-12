# Queueing

This package does not enqueue jobs internally. If you want queueing, do it in your Laravel app.

## Minimal queued job

```php
<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Muensmedia\HyvorRelay\Facades\HyvorRelay;
use Muensmedia\HyvorRelay\Data\Console\Requests\SendEmailPayloadData;

class SendWelcomeEmailJob implements ShouldQueue
{
    public int $tries = 3;

    public function __construct(
        public int $userId,
        public string $email,
    ) {}

    public function backoff(): array
    {
        return [30, 120, 300];
    }

    public function handle(): void
    {
        HyvorRelay::sendEmail(SendEmailPayloadData::from([
            'from' => 'app@example.com',
            'to' => $this->email,
            'subject' => 'Welcome',
            'body_text' => 'Thanks for signing up.',
        ]), "welcome-email-{$this->userId}");
    }
}
```

Dispatch it from your app code with `dispatch(new SendWelcomeEmailJob($user->id, $user->email));`.

## Retry behavior

- Retry `429` and `5xx` with the same idempotency key.
- Do not blindly retry other `4xx` responses.
