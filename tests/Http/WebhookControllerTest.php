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

it('rejects webhook requests with missing signature header', function () {
    $response = $this->postJson('/api/hyvor-relay/v1/webhook', [
        'event' => EventTypes::SEND_RECIPIENT_ACCEPTED->value,
        'payload' => [],
    ]);

    $response->assertUnauthorized();
});

it('accepts webhook requests with a valid sha256 prefixed signature', function () {
    Event::fake();

    $body = MockWebhooks::accepted();
    $json = json_encode($body, JSON_THROW_ON_ERROR);
    $signature = hash_hmac('sha256', $json, (string) config('hyvor-relay.webhook_secret'));

    $response = test()->call('POST', '/api/hyvor-relay/v1/webhook', [], [], [], [
        'CONTENT_TYPE' => 'application/json',
        'HTTP_X_SIGNATURE' => 'sha256='.$signature,
    ], $json);

    $response->assertNoContent();
    Event::assertDispatched(SendRecipientAcceptedReceived::class);
});

it('returns server error when webhook secret is missing', function () {
    config()->set('hyvor-relay.webhook_secret', null);

    $response = $this
        ->withHeaders([
            'X-Signature' => 'whatever',
        ])
        ->postJson('/api/hyvor-relay/v1/webhook', [
            'event' => EventTypes::SEND_RECIPIENT_ACCEPTED->value,
            'payload' => [],
        ]);

    $response->assertStatus(500);
});
