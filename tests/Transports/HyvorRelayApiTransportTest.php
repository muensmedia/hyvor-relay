<?php

use Illuminate\Support\Arr;
use Muensmedia\HyvorRelay\Transport\HyvorRelayApiTransport;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

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

test('payload includes body_text when email has a text part', function () {
    config()->set('hyvor-relay.endpoint', 'https://relay.hyvor.com');

    $captured = null;
    $client = new MockHttpClient(function (string $method, string $url, array $options) use (&$captured) {
        $captured = compact('method', 'url', 'options');

        return new MockResponse(
            json_encode(['id' => 1, 'message_id' => 'mid-123'], JSON_THROW_ON_ERROR),
            [
                'http_code' => 200,
                'response_headers' => ['content-type: application/json'],
            ]
        );
    });

    $transport = new HyvorRelayApiTransport('12345', $client);

    $email = new Email()
        ->from('from@example.com')
        ->to('to@example.com')
        ->subject('Subject')
        ->html('<p>Hello</p>')
        ->text('Hello (plain)');

    $sentMessage = $transport->send($email);

    $headers = Arr::get($captured, 'options.headers', []);
    $authorization = null;
    if (is_array($headers) && array_is_list($headers)) {
        foreach ($headers as $headerLine) {
            if (! is_string($headerLine)) {
                continue;
            }

            if (stripos($headerLine, 'authorization:') === 0) {
                $authorization = trim(substr($headerLine, strlen('authorization:')));
                break;
            }
        }
    } elseif (is_array($headers)) {
        $authorization = $headers['Authorization'] ?? $headers['authorization'] ?? null;
    }

    expect($captured)->not->toBeNull()
        ->and($captured['method'])->toBe('POST')
        ->and($captured['url'])->toBe('https://relay.hyvor.com/api/console/sends')
        ->and($authorization)->toBe('Bearer 12345')
        ->and(Arr::get($captured, 'options.json.body_html'))->toBe('<p>Hello</p>')
        ->and(Arr::get($captured, 'options.json.body_text'))->toBe('Hello (plain)')
        ->and($sentMessage?->getMessageId())->toBe('mid-123');
});
