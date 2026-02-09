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

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Muensmedia\\HyvorRelay\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );

        // Provide a config array to DataConfig
        $this->app->when(DataConfig::class)
            ->needs('$config')
            ->give([]);
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelDataServiceProvider::class,
            HyvorRelayServiceProvider::class,
            ActionServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        /*
        $migration = include __DIR__.'/../database/migrations/create_hyvor_relay_table.php.stub';
        $migration->up();
        */
    }
}
