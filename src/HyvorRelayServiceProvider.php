<?php

namespace Muensmedia\HyvorRelay;

use Illuminate\Support\Facades\Mail;
use Muensmedia\HyvorRelay\Support\Http\HyvorRelayHttpFactory;
use Muensmedia\HyvorRelay\Transport\HyvorRelayTransportFactory;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Symfony\Component\Mailer\Transport\Dsn;

class HyvorRelayServiceProvider extends PackageServiceProvider
{
    public function registeringPackage(): void
    {
        $this->app->singleton(HyvorRelay::class);
        $this->app->alias(HyvorRelay::class, 'hyvor-relay');
        $this->app->singleton(HyvorRelayHttpFactory::class);
    }

    public function configurePackage(Package $package): void
    {
        $package
            ->name('hyvor-relay')
            ->hasRoute('api')
            ->hasConfigFile();
    }

    public function bootingPackage(): void
    {
        Mail::extend('hyvor-relay', function () {
            return new HyvorRelayTransportFactory()->create(
                new Dsn(
                    'hyvor+api',
                    'default',
                    config('hyvor-relay.api_keys.transport')
                )
            );
        });
    }
}
