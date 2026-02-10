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
    $email->getHeaders()->addTextHeader('X-Idempotency-Key', 'welcome-email-123');

    $sentMessage = $transport->send($email);

    $payload = Arr::get($captured, 'options.json');
    if ($payload === null) {
        // Symfony HttpClient may normalize the "json" option into a JSON-encoded "body".
        $body = Arr::get($captured, 'options.body');
        if (is_string($body)) {
            $payload = json_decode($body, true);
        } elseif (is_array($body)) {
            $payload = $body;
        }
    }

    $getHeader = function (string $name) use ($captured) {
        $headers = Arr::get($captured, 'options.headers', []);
        if (! is_array($headers)) {
            return null;
        }

        if (array_is_list($headers)) {
            foreach ($headers as $headerLine) {
                if (! is_string($headerLine)) {
                    continue;
                }

                $prefix = strtolower($name).':';
                if (strtolower(substr($headerLine, 0, strlen($prefix))) === $prefix) {
                    return trim(substr($headerLine, strlen($prefix)));
                }
            }

            return null;
        }

        return $headers[$name] ?? $headers[strtolower($name)] ?? null;
    };

    expect($captured)->not->toBeNull()
        ->and($captured['method'])->toBe('POST')
        ->and($captured['url'])->toBe(config('hyvor-relay.endpoint').'/api/console/sends')
        ->and($getHeader('Authorization'))->toBe('Bearer 12345')
        ->and($getHeader('X-Idempotency-Key'))->toBe('welcome-email-123')
        ->and(Arr::get($payload, 'body_html'))->toBe('<p>Hello</p>')
        ->and(Arr::get($payload, 'body_text'))->toBe('Hello (plain)')
        ->and($sentMessage?->getMessageId())->toBe('mid-123');
});
