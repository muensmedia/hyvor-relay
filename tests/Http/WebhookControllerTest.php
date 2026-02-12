<?php

use Illuminate\Support\Facades\Event;
use Muensmedia\HyvorRelay\Enum\EventTypes;
use Muensmedia\HyvorRelay\Events\Webhooks\SendRecipientAcceptedReceived;
use Muensmedia\HyvorRelay\Exceptions\MissingWebhookTokenConfigurationException;

function postSignedWebhook(array $body)
{
    $json = json_encode($body, JSON_THROW_ON_ERROR);
    $signature = hash_hmac('sha256', $json, (string) config('hyvor-relay.webhook_secret'));
    $tokenParam = (string) config('hyvor-relay.webhook_token_query_parameter', 'token');
    $token = (string) config('hyvor-relay.webhook_token');
    $url = '/api/hyvor-relay/v1/webhook?' . http_build_query([$tokenParam => $token]);

    return test()->call('POST', $url, [], [], [], [
        'CONTENT_TYPE' => 'application/json',
        'HTTP_X_SIGNATURE' => $signature,
    ], $json);
}

it('dispatches the mapped laravel event with typed dto payload', function () {
    Event::fake();

    $response = postSignedWebhook([
        'event' => EventTypes::SEND_RECIPIENT_ACCEPTED->value,
        'payload' => [
            'send' => [
                'id' => 16,
                'uuid' => '15ff0c65-49bb-435a-9827-6a093153ef20',
                'created_at' => 1770884881,
                'from_address' => 'app@example.test',
                'from_name' => null,
                'subject' => 'Test Mail',
                'body_html' => null,
                'body_text' => null,
                'headers' => [],
                'raw' => '',
                'size_bytes' => 1654,
                'queued' => false,
                'send_after' => 1770884881,
                'recipients' => [
                    [
                        'id' => 16,
                        'type' => 'to',
                        'address' => 'john@example.test',
                        'name' => '',
                        'status' => 'accepted',
                        'try_count' => 1,
                    ],
                ],
                'attempts' => [],
                'feedback' => [],
            ],
            'recipient' => [
                'id' => 16,
                'type' => 'to',
                'address' => 'john@example.test',
                'name' => '',
                'status' => 'accepted',
                'try_count' => 1,
            ],
            'attempt' => [
                'id' => 16,
                'created_at' => 1770884881,
                'status' => 'accepted',
                'try_count' => 1,
                'domain' => 'example.test',
                'resolved_mx_hosts' => ['mx.example.test'],
                'responded_mx_host' => 'mx.example.test',
                'smtp_conversations' => [],
                'recipient_ids' => [16],
                'recipients' => [],
                'duration_ms' => 1192,
                'error' => null,
            ],
        ],
    ]);

    $response->assertNoContent();

    Event::assertDispatched(SendRecipientAcceptedReceived::class, function (SendRecipientAcceptedReceived $event) {
        return $event->payload->recipient->address === 'john@example.test'
            && $event->payload->attempt->status === 'accepted';
    });
});

it('returns accepted for unsupported webhook events', function () {
    Event::fake();

    $response = postSignedWebhook([
        'event' => 'unsupported.event',
        'payload' => [],
    ]);

    $response->assertNoContent();
    Event::assertNotDispatched(SendRecipientAcceptedReceived::class);
});

it('rejects webhook requests with invalid signature', function () {
    $response = $this
        ->withHeaders([
            'X-Signature' => 'invalid',
        ])
        ->postJson('/api/hyvor-relay/v1/webhook?token=test-token', [
            'event' => EventTypes::SEND_RECIPIENT_ACCEPTED->value,
            'payload' => [],
        ]);

    $response->assertUnauthorized();
});

it('rejects webhook requests with invalid token', function () {
    $signature = hash_hmac('sha256', '{}', (string) config('hyvor-relay.webhook_secret'));

    $response = $this
        ->withHeaders([
            'X-Signature' => $signature,
        ])
        ->postJson('/api/hyvor-relay/v1/webhook?token=wrong-token', [
            // Keep body exactly "{}" for deterministic signature.
        ]);

    $response->assertUnauthorized();
});

it('throws a dedicated exception when webhook token config is missing', function () {
    config()->set('hyvor-relay.webhook_token', null);

    $this->withoutExceptionHandling();

    $this->expectException(MissingWebhookTokenConfigurationException::class);

    $this->postJson('/api/hyvor-relay/v1/webhook?token=test-token', [
        'event' => EventTypes::SEND_RECIPIENT_ACCEPTED->value,
        'payload' => [],
    ]);
});
