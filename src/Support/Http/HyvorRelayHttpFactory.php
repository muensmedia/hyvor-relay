<?php

namespace Muensmedia\HyvorRelay\Support\Http;

use Illuminate\Http\Client\Factory;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\App;

class HyvorRelayHttpFactory extends Factory
{
    protected function newPendingRequest(): PendingRequest
    {
        return parent::newPendingRequest()
            ->withUserAgent(self::buildUserAgent())
            ->asJson()
            ->acceptJson()
            ->timeout((int) config('hyvor-relay.timeout', 10));
    }

    private static function buildUserAgent(): string
    {
        return sprintf(
            'Muensmedia HyvorRelay | %s (ENV: %s; URL: %s)',
            (string) config('app.name', 'laravel'),
            App::environment(),
            (string) config('app.url', 'n/a')
        );
    }
}
