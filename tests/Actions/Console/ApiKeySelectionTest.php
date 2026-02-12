<?php

use Illuminate\Http\Client\Request;
use Muensmedia\HyvorRelay\Actions\Console\Analytics\GetAnalyticsStatsAction;
use Muensmedia\HyvorRelay\Actions\Console\Sends\SendEmailAction;
use Muensmedia\HyvorRelay\Facades\HyvorRelayHttp;

it('uses send api key for sends endpoints', function () {
    config()->set('hyvor-relay.api_keys.send', 'send-key-123');
    config()->set('hyvor-relay.api_keys.general', 'general-key-123');

    $captured = null;

    HyvorRelayHttp::fake(function (Request $request) use (&$captured) {
        $captured = $request;

        return HyvorRelayHttp::response([
            'id' => 99,
            'message_id' => 'msg-99',
        ], 200);
    });

    SendEmailAction::run([
        'from' => 'app@example.test',
        'to' => 'john@example.test',
        'subject' => 'Hello',
        'body_text' => 'Hi',
    ]);

    expect($captured)->not->toBeNull();
    expect($captured->header('Authorization')[0])->toBe('Bearer send-key-123');
});

it('uses general api key for non-sends console endpoints', function () {
    config()->set('hyvor-relay.api_keys.send', 'send-key-123');
    config()->set('hyvor-relay.api_keys.general', 'general-key-123');

    $captured = null;

    HyvorRelayHttp::fake(function (Request $request) use (&$captured) {
        $captured = $request;

        return HyvorRelayHttp::response([
            'sends' => 1,
            'bounce_rate' => 0.0,
            'complaint_rate' => 0.0,
        ], 200);
    });

    GetAnalyticsStatsAction::run();

    expect($captured)->not->toBeNull();
    expect($captured->header('Authorization')[0])->toBe('Bearer general-key-123');
});
