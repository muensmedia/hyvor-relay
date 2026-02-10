<?php

namespace Muensmedia\HyvorRelay\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Lorisleiva\Actions\ActionServiceProvider;
use Muensmedia\HyvorRelay\HyvorRelayServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\LaravelData\LaravelDataServiceProvider;
use Spatie\LaravelData\Support\DataConfig;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        // Provide a config array to DataConfig
        $this->app->when(DataConfig::class)
            ->needs('$config')
            ->give([]);
    }

    protected function getPackageProviders($app): array
    {
        return [
            LaravelDataServiceProvider::class,
            HyvorRelayServiceProvider::class,
            ActionServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app): void
    {
        config()->set('database.default', 'testing');
        config()->set('mail.default', 'hyvor');
        config()->set('mail.mailers.hyvor', [
            'transport' => 'hyvor-relay',
        ]);
    }
}
