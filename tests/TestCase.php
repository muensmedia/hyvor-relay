<?php

namespace Muensmedia\HyvorRelay\Tests;

use Lorisleiva\Actions\ActionServiceProvider;
use Muensmedia\HyvorRelay\HyvorRelayServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\LaravelData\LaravelDataServiceProvider;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            ...parent::getPackageProviders($app),
            HyvorRelayServiceProvider::class,
            LaravelDataServiceProvider::class,
            ActionServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app): void
    {
        config()->set('database.default', 'testing');
        config()->set('hyvor-relay.webhook_secret', 'test-secret');
        config()->set('mail.default', 'hyvor');
        config()->set('mail.mailers.hyvor', [
            'transport' => 'hyvor-relay',
        ]);
    }
}
