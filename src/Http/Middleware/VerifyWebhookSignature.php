<?php

namespace Muensmedia\HyvorRelay\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Muensmedia\HyvorRelay\HyvorRelay;
use Symfony\Component\HttpFoundation\Response;

class VerifyWebhookSignature
{
    public function handle(Request $request, Closure $next, ?string $secretOrConfig = null): Response
    {
        $secret = $this->resolveSecret($secretOrConfig);

        abort_if($secret === '', 500, 'HYVOR_RELAY_WEBHOOK_SECRET is not configured.');

        $receivedSignature = trim((string) $request->header('X-Signature', ''));

        abort_if($receivedSignature === '', 401, 'Missing webhook signature.');

        abort_unless(app(HyvorRelay::class)->verifyWebhookSignature($request->getContent(), $receivedSignature, $secret), 401, 'Invalid webhook signature.');

        return $next($request);
    }

    protected function resolveSecret(?string $secretOrConfig): string
    {
        if ($secretOrConfig === null || $secretOrConfig === '') {
            return (string) config('hyvor-relay.webhook_secret', '');
        }

        if (Str::startsWith($secretOrConfig, 'config:')) {
            return (string) config(substr($secretOrConfig, 7), '');
        }

        return $secretOrConfig;
    }
}
