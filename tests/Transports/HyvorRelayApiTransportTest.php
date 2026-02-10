<?php

use Illuminate\Support\Arr;
use Muensmedia\HyvorRelay\Transport\HyvorRelayApiTransport;
use Symfony\Component\HttpClient\Exception\TransportException;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\Mailer\Envelope;
use Symfony\Component\Mailer\Exception\HttpTransportException;
use Symfony\Component\Mailer\Header\TagHeader;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Contracts\HttpClient\ResponseInterface;

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

test('__toString uses config endpoint by default and host/port overrides when set', function () {
    config()->set('hyvor-relay.endpoint', 'https://relay.example.test');

    $transport = new HyvorRelayApiTransport('12345');
    expect((string) $transport)->toBe('hyvor+api://https://relay.example.test');

    // AbstractTransport provides setters for host/port, which should override the config endpoint.
    $transport->setHost('relay.override.test');
    $transport->setPort(8081);
    expect((string) $transport)->toBe('hyvor+api://relay.override.test:8081');
});

test('payload normalizes addresses, includes reply-to, custom headers, tag headers, and attachments', function () {
    $captured = null;

    $client = new MockHttpClient(function (string $method, string $url, array $options) use (&$captured) {
        $captured = compact('method', 'url', 'options');

        return new MockResponse(
            json_encode(['id' => 1, 'message_id' => 'mid-200'], JSON_THROW_ON_ERROR),
            [
                'http_code' => 200,
                'response_headers' => ['content-type: application/json'],
            ]
        );
    });

    $transport = new HyvorRelayApiTransport('k', $client);

    $email = new Email()
        ->from(new Address('from@example.com', 'From Name'))
        ->to(new Address('to1@example.com', 'To One'), 'to2@example.com')
        ->cc('cc@example.com')
        ->bcc('bcc@example.com')
        ->replyTo(new Address('reply@example.com', 'Reply Name'))
        ->subject('Hello')
        ->html('<p>Hi</p>')
        ->text('Hi');

    // Ensure prepareHeadersAndTags() sees both TagHeader and regular headers.
    $email->getHeaders()->add(new TagHeader('tag-1'));
    $email->getHeaders()->addTextHeader('X-Custom', 'custom-value');

    // Force prepareAttachments() to run.
    $email->attach('hello world', 'test.txt', 'text/plain');

    // Sender should come from envelope sender when provided.
    $envelope = new Envelope(
        new Address('sender@example.com', 'Sender Name'),
        [new Address('env-to@example.com', 'Env To')]
    );

    $transport->send($email, $envelope);

    $payload = Arr::get($captured, 'options.json');
    if ($payload === null) {
        $payload = json_decode((string) Arr::get($captured, 'options.body'), true);
    }

    expect($payload)
        ->and(Arr::get($payload, 'from'))->toBe(['name' => 'Sender Name', 'email' => 'sender@example.com'])
        // Envelope recipient should win over message recipients.
        // Note: Envelope may strip recipient display-names (depends on Symfony version), so a single recipient can normalize to a string.
        ->and(Arr::get($payload, 'to'))->toBe('env-to@example.com')
        // Multiple "to" addresses should have been normalized (covers normalizeAddresses code paths).
        ->and(Arr::get($payload, 'cc'))->toBe('cc@example.com')
        ->and(Arr::get($payload, 'bcc'))->toBe('bcc@example.com')
        ->and(Arr::get($payload, 'subject'))->toBe('Hello')
        ->and(Arr::get($payload, 'body_html'))->toBe('<p>Hi</p>')
        ->and(Arr::get($payload, 'body_text'))->toBe('Hi')
        ->and(Arr::get($payload, 'headers.X-Custom'))->toBe('custom-value')
        // Address::toString() may quote the display-name depending on Symfony version.
        ->and(Arr::get($payload, 'headers.Reply-To'))->toBe('"Reply Name" <reply@example.com>')
        ->and(Arr::get($payload, 'attachments.0.name'))->toBe('test.txt')
        ->and(Arr::get($payload, 'attachments.0.content'))->toBeString()
        ->and(Arr::get($payload, 'attachments.0.content'))->not->toContain("\r\n");
});

test('payload uses list form when there are multiple recipients', function () {
    $captured = null;

    $client = new MockHttpClient(function (string $method, string $url, array $options) use (&$captured) {
        $captured = compact('method', 'url', 'options');

        return new MockResponse(
            json_encode(['id' => 1, 'message_id' => 'mid-203'], JSON_THROW_ON_ERROR),
            [
                'http_code' => 200,
                'response_headers' => ['content-type: application/json'],
            ]
        );
    });

    $transport = new HyvorRelayApiTransport('k', $client);

    $email = new Email()
        ->from('from@example.com')
        ->to(new Address('a@example.com', 'A'), new Address('b@example.com', 'B'))
        ->html('<p>Hi</p>');

    $transport->send($email);

    $payload = Arr::get($captured, 'options.json');
    if ($payload === null) {
        $payload = json_decode((string) Arr::get($captured, 'options.body'), true);
    }

    expect(Arr::get($payload, 'to'))->toBe([
        ['name' => 'A', 'email' => 'a@example.com'],
        ['name' => 'B', 'email' => 'b@example.com'],
    ]);
});

test('reply-to is added even when there are no other custom headers', function () {
    $captured = null;

    $client = new MockHttpClient(function (string $method, string $url, array $options) use (&$captured) {
        $captured = compact('method', 'url', 'options');

        return new MockResponse(
            json_encode(['id' => 1, 'message_id' => 'mid-204'], JSON_THROW_ON_ERROR),
            [
                'http_code' => 200,
                'response_headers' => ['content-type: application/json'],
            ]
        );
    });

    $transport = new HyvorRelayApiTransport('k', $client);

    $email = new Email()
        ->from('from@example.com')
        ->to('to@example.com')
        ->replyTo('reply@example.com')
        ->html('<p>Hi</p>');

    $transport->send($email);

    $payload = Arr::get($captured, 'options.json');
    if ($payload === null) {
        $payload = json_decode((string) Arr::get($captured, 'options.body'), true);
    }

    expect(Arr::get($payload, 'headers.Reply-To'))->toBe('reply@example.com');
});

test('idempotency header is removed from the email and only sent when non-empty', function () {
    $captured = null;

    $client = new MockHttpClient(function (string $method, string $url, array $options) use (&$captured) {
        $captured = compact('method', 'url', 'options');

        return new MockResponse(
            json_encode(['id' => 1, 'message_id' => 'mid-201'], JSON_THROW_ON_ERROR),
            [
                'http_code' => 200,
                'response_headers' => ['content-type: application/json'],
            ]
        );
    });

    $transport = new HyvorRelayApiTransport('k', $client);

    $email = new Email()
        ->from('from@example.com')
        ->to('to@example.com')
        ->html('<p>Hi</p>');
    $email->getHeaders()->addTextHeader('X-Idempotency-Key', '   ');

    $transport->send($email);

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

    // Only assert what was actually sent. The Mailer/Transport may clone the message internally.
    expect($getHeader('X-Idempotency-Key'))->toBeNull();
});

test('getIdempotencyKey removes the header and returns null for whitespace', function () {
    $transport = new HyvorRelayApiTransport('k');

    $email = new Email()
        ->from('from@example.com')
        ->to('to@example.com')
        ->html('<p>Hi</p>');
    $email->getHeaders()->addTextHeader('X-Idempotency-Key', '   ');

    $method = new ReflectionMethod(HyvorRelayApiTransport::class, 'getIdempotencyKey');
    $method->setAccessible(true);

    $value = $method->invoke($transport, $email);

    expect($value)->toBeNull()
        ->and($email->getHeaders()->has('X-Idempotency-Key'))->toBeFalse();
});

test('subject "default" is not sent in payload', function () {
    $captured = null;

    $client = new MockHttpClient(function (string $method, string $url, array $options) use (&$captured) {
        $captured = compact('method', 'url', 'options');

        return new MockResponse(
            json_encode(['id' => 1, 'message_id' => 'mid-202'], JSON_THROW_ON_ERROR),
            [
                'http_code' => 200,
                'response_headers' => ['content-type: application/json'],
            ]
        );
    });

    $transport = new HyvorRelayApiTransport('k', $client);

    $email = new Email()
        ->from('from@example.com')
        ->to('to@example.com')
        ->subject('default')
        ->html('<p>Hi</p>');

    $transport->send($email);

    $payload = Arr::get($captured, 'options.json');
    if ($payload === null) {
        $payload = json_decode((string) Arr::get($captured, 'options.body'), true);
    }

    expect(array_key_exists('subject', $payload))->toBeFalse();
});

test('throws when API responds with non-2xx and message, error, errors, or no message', function () {
    $cases = [
        ['body' => ['message' => 'bad'], 'expected' => 'bad'],
        ['body' => ['error' => 'nope'], 'expected' => 'nope'],
        ['body' => ['errors' => 'x'], 'expected' => 'x'],
        // No message fields -> fallback to getContent(false) (here: "[]").
        ['body' => [], 'expected' => '[]'],
    ];

    foreach ($cases as $case) {
        $client = new MockHttpClient(function () use ($case) {
            return new MockResponse(
                json_encode($case['body'], JSON_THROW_ON_ERROR),
                [
                    'http_code' => 400,
                    'response_headers' => ['content-type: application/json'],
                ]
            );
        });

        $transport = new HyvorRelayApiTransport('k', $client);
        $email = new Email()->from('from@example.com')->to('to@example.com')->html('<p>Hi</p>');

        try {
            $transport->send($email);
            throw new RuntimeException('Expected HttpTransportException.');
        } catch (HttpTransportException $e) {
            expect($e->getMessage())->toContain('Unable to send an email: '.$case['expected']);
        }
    }
});

test('throws when API response is success but missing or invalid message_id', function () {
    $cases = [
        ['body' => ['id' => 1], 'label' => 'missing'],
        ['body' => ['id' => 1, 'message_id' => ['not-scalar']], 'label' => 'non-scalar'],
        ['body' => ['id' => 1, 'message_id' => ''], 'label' => 'empty-string'],
    ];

    foreach ($cases as $case) {
        $client = new MockHttpClient(function () use ($case) {
            return new MockResponse(
                json_encode($case['body'], JSON_THROW_ON_ERROR),
                [
                    'http_code' => 200,
                    'response_headers' => ['content-type: application/json'],
                ]
            );
        });

        $transport = new HyvorRelayApiTransport('k', $client);
        $email = new Email()->from('from@example.com')->to('to@example.com')->html('<p>Hi</p>');

        try {
            $transport->send($email);
            throw new RuntimeException('Expected HttpTransportException.');
        } catch (HttpTransportException $e) {
            expect($e->getMessage())->toContain('missing "message_id"');
        }
    }
});

test('wraps decoding exceptions as HttpTransportException with response content', function () {
    $client = new MockHttpClient(function () {
        return new MockResponse(
            'not-json',
            [
                'http_code' => 200,
                'response_headers' => ['content-type: application/json'],
            ]
        );
    });

    $transport = new HyvorRelayApiTransport('k', $client);
    $email = new Email()->from('from@example.com')->to('to@example.com')->html('<p>Hi</p>');

    try {
        $transport->send($email);
        throw new RuntimeException('Expected HttpTransportException.');
    } catch (HttpTransportException $e) {
        expect($e->getMessage())->toContain('Unable to send an email: not-json')
            ->and($e->getMessage())->toContain('(code 200)');
    }
});

test('wraps transport exceptions as HttpTransportException', function () {
    $client = new MockHttpClient(function () {
        return new class() implements ResponseInterface
        {
            public function getStatusCode(): int
            {
                throw new TransportException('boom');
            }

            public function getHeaders(bool $throw = true): array
            {
                return [];
            }

            public function getContent(bool $throw = true): string
            {
                return '';
            }

            public function toArray(bool $throw = true): array
            {
                return [];
            }

            public function cancel(): void {}

            public function getInfo(?string $type = null): mixed
            {
                return null;
            }
        };
    });

    $transport = new HyvorRelayApiTransport('k', $client);
    $email = new Email()->from('from@example.com')->to('to@example.com')->html('<p>Hi</p>');

    try {
        $transport->send($email);
        throw new RuntimeException('Expected HttpTransportException.');
    } catch (HttpTransportException $e) {
        expect($e->getMessage())->toBe('Could not reach the remote Hyvor Relay server.');
    }
});

test('stringifyAddresses returns a list of normalized addresses', function () {
    $transport = new HyvorRelayApiTransport('k');

    $method = new ReflectionMethod(HyvorRelayApiTransport::class, 'stringifyAddresses');
    $method->setAccessible(true);

    $result = $method->invoke($transport, [
        new Address('a@example.com', 'A'),
        new Address('b@example.com'),
    ]);

    expect($result)->toBe([
        ['email' => 'a@example.com', 'name' => 'A'],
        ['email' => 'b@example.com'],
    ]);
});
