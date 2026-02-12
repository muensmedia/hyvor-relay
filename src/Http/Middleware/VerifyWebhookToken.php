<?php

namespace Muensmedia\HyvorRelay\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Muensmedia\HyvorRelay\Exceptions\MissingWebhookTokenConfigurationException;
use Symfony\Component\HttpFoundation\Response;

class VerifyWebhookToken
{
    public function handle(Request $request, Closure $next): Response
    {
        $configuredToken = (string) config('hyvor-relay.webhook_token', '');

        if ($configuredToken === '') {
            throw MissingWebhookTokenConfigurationException::make();
        }

        $queryParameter = (string) config('hyvor-relay.webhook_token_query_parameter', 'token');
        $receivedToken = (string) $request->query($queryParameter, '');

        if ($receivedToken === '' || ! hash_equals($configuredToken, $receivedToken)) {
            abort(401, 'Invalid webhook token.');
        }

        return $next($request);
    }
}
