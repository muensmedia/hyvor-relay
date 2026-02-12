<?php

namespace Muensmedia\HyvorRelay\Tests\Support;

use Muensmedia\HyvorRelay\Enum\EventTypes;

class MockWebhooks
{
    public static function accepted(array $overrides = []): array
    {
        return self::mergeRecursiveDistinct([
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
        ], $overrides);
    }

    public static function unsupported(array $payload = []): array
    {
        return [
            'event' => 'unsupported.event',
            'payload' => $payload,
        ];
    }

    private static function mergeRecursiveDistinct(array $base, array $overrides): array
    {
        foreach ($overrides as $key => $value) {
            if (is_array($value) && isset($base[$key]) && is_array($base[$key])) {
                $base[$key] = self::mergeRecursiveDistinct($base[$key], $value);

                continue;
            }
            $base[$key] = $value;
        }

        return $base;
    }
}
