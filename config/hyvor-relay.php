<?php

return [
    /**
     * Hyvor Relay API key used for authentication.
     *
     * Sent as: Authorization: Bearer {api_key}
     */
    'api_key' => env('HYVOR_RELAY_API_KEY', 'your-api-key-here'),

    /**
     * Base URL of your Relay server (Hyvor-hosted or self-hosted).
     *
     * Important:
     * - Use the base URL only (no trailing "/api/..." path).
     * - Example: "https://relay.hyvor.com"
     *
     * The transport will call:
     * - POST {endpoint}/api/console/sends
     */
    'endpoint' => env('HYVOR_RELAY_ENDPOINT', 'https://relay.hyvor.com'),
];
