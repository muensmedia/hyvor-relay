<?php

use Illuminate\Support\Facades\Event;
use Muensmedia\HyvorRelay\Enum\EventTypes;
use Muensmedia\HyvorRelay\Events\Webhooks\SendRecipientAcceptedReceived;
use Muensmedia\HyvorRelay\Tests\Support\MockWebhooks;

function postSignedWebhook(array $body)
{
    $json = json_encode($body, JSON_THROW_ON_ERROR);
    $signature = hash_hmac('sha256', $json, (string) config('hyvor-relay.webhook_secret'));

    return test()->call('POST', '/api/hyvor-relay/v1/webhook', [], [], [], [
        'CONTENT_TYPE' => 'application/json',
        'HTTP_X_SIGNATURE' => $signature,
    ], $json);
}

it('dispatches the mapped laravel event with typed dto payload', function () {
    Event::fake();

    $response = postSignedWebhook(MockWebhooks::accepted());

    $response->assertNoContent();

    Event::assertDispatched(SendRecipientAcceptedReceived::class, function (SendRecipientAcceptedReceived $event) {
        return $event->payload->recipient->address === 'john@example.test'
            && $event->payload->attempt->status === 'accepted';
    });
});

it('returns accepted for unsupported webhook events', function () {
    Event::fake();

    $response = postSignedWebhook(MockWebhooks::unsupported());

    $response->assertNoContent();
    Event::assertNotDispatched(SendRecipientAcceptedReceived::class);
});

it('rejects webhook requests with invalid signature', function () {
    $response = $this
        ->withHeaders([
            'X-Signature' => 'invalid',
        ])
        ->postJson('/api/hyvor-relay/v1/webhook', [
            'event' => EventTypes::SEND_RECIPIENT_ACCEPTED->value,
            'payload' => [],
        ]);

    $response->assertUnauthorized();
});
