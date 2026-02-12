<?php

use Muensmedia\HyvorRelay\Facades\HyvorRelay;

it('can fake and assert api calls via facade', function () {
    HyvorRelay::fake()
        ->setResponse('sendEmail', ['id' => 42, 'message_id' => 'm-1']);

    $response = HyvorRelay::sendEmail([
        'from' => 'app@example.test',
        'to' => 'john@example.test',
        'subject' => 'Hello',
        'body_text' => 'Hi',
    ], 'welcome-1');

    expect($response)->toBe(['id' => 42, 'message_id' => 'm-1']);

    HyvorRelay::assertCalled('sendEmail');
    HyvorRelay::assertRequested(fn (array $call): bool => ($call['arguments'][1] ?? null) === 'welcome-1');
});

it('can assert that no api calls were made', function () {
    HyvorRelay::fake();

    HyvorRelay::assertNothingRequested();
});
