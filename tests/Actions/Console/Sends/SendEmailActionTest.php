<?php

use Illuminate\Http\Client\Request;
use Muensmedia\HyvorRelay\Actions\Console\Sends\SendEmailAction;
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

    $result = SendEmailAction::run([
        'from' => 'app@example.test',
        'to' => 'john@example.test',
        'subject' => 'Hello',
        'body_text' => 'Hi',
    ], 'welcome-7');

    expect($result->id)->toBe(7);

    expect($capturedRequest)->not->toBeNull();
    expect($capturedRequest->method())->toBe('POST');
    expect($capturedRequest->url())
        ->toBe(rtrim((string) config('hyvor-relay.endpoint'), '/').'/api/console/sends');
    expect($capturedRequest->header('X-Idempotency-Key')[0])->toBe('welcome-7');
    expect($capturedRequest['subject'])->toBe('Hello');
});
