<?php

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Illuminate\Support\Facades\Mail;

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
            // Here you would return an instance of your custom mail transport
            // For example:
            // return new HyvorRelayTransport(config('hyvor-relay.api_key'));
        });
    }
}