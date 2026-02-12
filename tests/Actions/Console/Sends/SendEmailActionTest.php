<?php

use Illuminate\Http\Client\Request;
use Muensmedia\HyvorRelay\Actions\Console\Sends\SendEmailAction;
use Muensmedia\HyvorRelay\Data\Console\Requests\SendEmailPayloadData;
use Muensmedia\HyvorRelay\Facades\HyvorRelayHttp;

it('sends request via action with idempotency header', function () {
    $capturedRequest = null;

    HyvorRelayHttp::fake(function (Request $request) use (&$capturedRequest) {
        $capturedRequest = $request;

        return HyvorRelayHttp::response([
            'id' => 7,
            'message_id' => 'msg-7',
        ], 200);
    });

    $payload = SendEmailPayloadData::from([
        'from' => 'app@example.test',
        'to' => 'john@example.test',
        'subject' => 'Hello',
        'body_text' => 'Hi',
    ]);

    $result = SendEmailAction::run($payload, 'welcome-7');

    expect($result->id)->toBe(7)
        ->and($capturedRequest)->not->toBeNull()
        ->and($capturedRequest->method())->toBe('POST')
        ->and($capturedRequest->url())
        ->toBe(rtrim((string)config('hyvor-relay.endpoint'), '/') . '/api/console/sends')
        ->and($capturedRequest->header('X-Idempotency-Key')[0])->toBe('welcome-7')
        ->and($capturedRequest['subject'])->toBe('Hello');

});
