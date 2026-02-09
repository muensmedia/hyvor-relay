<?php

use Illuminate\Support\Arr;
use Muensmedia\HyvorRelay\Transports\BrevoApiTransport;
use Symfony\Component\Mime\Address;

test('stringifyAddress uses IDN converted domains', function () {
    // prepare
    $address = new Address('kältetechnik@kältetechnik.de', 'Max Müstermann');
    $brevoApiTransport = new BrevoApiTransport('12345');

    // act
    $result = $brevoApiTransport->stringifyAddress($address);

    // assert
    expect(Arr::get($result, 'email'))
        ->toBeString()
        ->toBe('kältetechnik@xn--kltetechnik-l8a.de')
        ->and(Arr::get($result, 'name'))
        ->toBeString()
        ->toBe('Max Müstermann');
});
