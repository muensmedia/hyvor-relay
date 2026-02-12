<?php

use Illuminate\Support\Facades\Mail;
use Muensmedia\HyvorRelay\Transport\HyvorRelayApiTransport;

it('uses configured transport api key', function () {
    config()->set('hyvor-relay.api_keys.transport', 'transport-key-456');

    /** @var HyvorRelayApiTransport $transport */
    $transport = Mail::mailer()->getSymfonyTransport();

    $reflection = new ReflectionClass($transport);
    $property = $reflection->getProperty('key');
    $property->setAccessible(true);

    expect($property->getValue($transport))->toBe('transport-key-456');
});
