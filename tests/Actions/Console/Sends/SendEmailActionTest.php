<?php

use Muensmedia\HyvorRelay\Actions\Console\Sends\SendEmailAction;
use Muensmedia\HyvorRelay\Fakes\HyvorRelayFake;
use Muensmedia\HyvorRelay\HyvorRelay;

it('runs send email action through hyvor relay service', function () {
    $fake = (new HyvorRelayFake())
        ->setResponse('POST', 'sends', ['id' => 7, 'message_id' => 'msg-7']);

    app()->instance(HyvorRelay::class, $fake);

    $result = SendEmailAction::run([
        'from' => 'app@example.test',
        'to' => 'john@example.test',
        'subject' => 'Hello',
        'body_text' => 'Hi',
    ]);

    expect($result['id'])->toBe(7);
    $fake->assertEndpointRequested('POST', 'sends');
});
