<?php

use Illuminate\Support\Arr;
use Muensmedia\HyvorRelay\Transport\HyvorRelayApiTransport;
use Symfony\Component\Mime\Address;

test('stringifyAddress uses IDN converted domains', function () {
    // prepare
    $address = new Address('kältetechnik@kältetechnik.de', 'Max Müstermann');
    $transport = new HyvorRelayApiTransport('12345');

    // act
    $result = $transport->stringifyAddress($address);

    // assert
    expect(Arr::get($result, 'email'))
        ->toBeString()
        ->toBe('kältetechnik@xn--kltetechnik-l8a.de')
        ->and(Arr::get($result, 'name'))
        ->toBeString()
        ->toBe('Max Müstermann');
});
