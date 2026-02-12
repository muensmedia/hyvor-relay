<?php

namespace Muensmedia\HyvorRelay\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyWebhookSignature
{
    public function handle(Request $request, Closure $next): Response
    {
        $secret = (string) config('hyvor-relay.webhook_secret', '');

        abort_if($secret === '', 500, 'HYVOR_RELAY_WEBHOOK_SECRET is not configured.');

        $receivedSignature = trim((string) $request->header('X-Signature', ''));

        abort_if($receivedSignature === '', 401, 'Missing webhook signature.');

        if (str_starts_with($receivedSignature, 'sha256=')) {
            $receivedSignature = substr($receivedSignature, 7);
        }

        $calculatedSignature = hash_hmac('sha256', $request->getContent(), $secret);

        abort_unless(hash_equals($calculatedSignature, $receivedSignature), 401, 'Invalid webhook signature.');

        return $next($request);
    }
}
