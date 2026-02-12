<?php

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Muensmedia\HyvorRelay\Enum\EventTypes;
use Muensmedia\HyvorRelay\Events\Webhooks\SendRecipientAcceptedReceived;
use Muensmedia\HyvorRelay\Facades\HyvorRelay;
use Muensmedia\HyvorRelay\Http\Middleware\VerifyWebhookSignature;
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

it('can verify signatures via helper methods', function () {
    $body = json_encode(MockWebhooks::accepted(), JSON_THROW_ON_ERROR);
    $secret = 'secret-123';
    $signature = HyvorRelay::signWebhookPayload($body, $secret);

    expect(HyvorRelay::verifyWebhookSignature($body, $signature, $secret))->toBeTrue();
    expect(HyvorRelay::verifyWebhookSignature($body, 'invalid', $secret))->toBeFalse();
});

it('supports middleware with config-based secret parameter for custom webhook routes', function () {
    config()->set('services.hyvor.alt_secret', 'alt-secret-123');

    Route::post('/api/hyvor-relay/v1/webhook-alt', fn () => response()->json(status: 204))
        ->middleware(VerifyWebhookSignature::class.':config:services.hyvor.alt_secret');

    $body = json_encode(MockWebhooks::accepted(), JSON_THROW_ON_ERROR);
    $signature = HyvorRelay::signWebhookPayload($body, 'alt-secret-123');

    $response = test()->call('POST', '/api/hyvor-relay/v1/webhook-alt', [], [], [], [
        'CONTENT_TYPE' => 'application/json',
        'HTTP_X_SIGNATURE' => $signature,
    ], $body);

    $response->assertNoContent();
});

it('supports middleware with inline secret parameter for custom webhook routes', function () {
    Route::post('/api/hyvor-relay/v1/webhook-inline-secret', fn () => response()->json(status: 204))
        ->middleware(VerifyWebhookSignature::class.':inline-secret-456');

    $body = json_encode(MockWebhooks::accepted(), JSON_THROW_ON_ERROR);
    $signature = HyvorRelay::signWebhookPayload($body, 'inline-secret-456');

    $response = test()->call('POST', '/api/hyvor-relay/v1/webhook-inline-secret', [], [], [], [
        'CONTENT_TYPE' => 'application/json',
        'HTTP_X_SIGNATURE' => $signature,
    ], $body);

    $response->assertNoContent();
});
