<?php

namespace Muensmedia\HyvorRelay;

use Illuminate\Support\Facades\Mail;
use Muensmedia\HyvorRelay\Transport\HyvorRelayTransportFactory;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Symfony\Component\Mailer\Transport\Dsn;

class HyvorRelayServiceProvider extends PackageServiceProvider
{

    public function configurePackage(Package $package): void
    {
        $package
            ->name('hyvor-relay')
            ->hasConfigFile();
    }

    public function bootingPackage(): void
    {
        Mail::extend('hyvor-relay', function () {
            return new HyvorRelayTransportFactory()->create(
                new Dsn(
                    'hyvor+api',
                    'default',
                    config('hyvor-relay.api_key')
                )
            );
        });
    }
}
