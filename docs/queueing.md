# Queueing Best Practices

This package intentionally keeps API calls synchronous and does not enqueue jobs internally. Queue orchestration should stay in the consuming application.

## Why Queue in the App

- Your app defines queue backend, retry policy, and alerting.
- You can tune backoff per use case.
- You keep full control over idempotency and deduplication.

## Recommended Strategy

For `sendEmail` calls to Hyvor Relay:

1. Dispatch a queued job from your app.
2. Retry up to 3 times with exponential backoff.
3. Always use an idempotency key for one-time emails.
4. Log and alert after final failure.

Suggested backoff:

- `30s`
- `120s`
- `300s`

## Example Job

```php
<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Muensmedia\HyvorRelay\Facades\HyvorRelay;

class SendWelcomeEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function backoff(): array
    {
        return [30, 120, 300];
    }

    public function __construct(
        public int $userId,
        public string $email,
    ) {}

    public function handle(): void
    {
        HyvorRelay::sendEmail([
            'from' => 'app@example.com',
            'to' => $this->email,
            'subject' => 'Welcome',
            'body_text' => 'Thanks for signing up.',
        ], \"welcome-email-{$this->userId}\");
    }
}
```

## Operational Notes

- For `429`, retry after `Retry-After` when available.
- For `5xx`, retry with the same idempotency key.
- For `4xx` (except `429`), fix request payload and use a new idempotency key.
