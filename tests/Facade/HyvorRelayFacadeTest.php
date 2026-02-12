<?php

use Muensmedia\HyvorRelay\Facades\HyvorRelay;

it('can fake and assert api calls via facade', function () {
    HyvorRelay::fake()
        ->setResponse('POST', 'sends', ['id' => 42, 'message_id' => 'm-1']);

    $response = HyvorRelay::sendEmail([
        'from' => 'app@example.test',
        'to' => 'john@example.test',
        'subject' => 'Hello',
        'body_text' => 'Hi',
    ], 'welcome-1');

    expect($response)->toBe(['id' => 42, 'message_id' => 'm-1']);

    HyvorRelay::assertEndpointRequested('POST', 'sends');
    HyvorRelay::assertRequested(fn (array $call): bool => ($call['headers']['X-Idempotency-Key'] ?? null) === 'welcome-1');
});

it('can assert that no api calls were made', function () {
    HyvorRelay::fake();

    HyvorRelay::assertNothingRequested();
});
