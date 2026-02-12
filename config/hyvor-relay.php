<?php

// Resolve one shared default key first; specific keys can override this per use-case.
$generalApiKey = env('HYVOR_RELAY_API_KEY_GENERAL', 'your-api-key-here');

return [
    /**
     * API keys per use-case.
     *
     * @see https://relay.hyvor.com/docs/api-console#api-usage
     *
     * Defaults:
     * - general: base key used for Console API requests
     * - send: used for Console API sends endpoints
     * - transport: used for Laravel mail transport driver
     *
     * Fallback behavior:
     * - send falls back to general if HYVOR_RELAY_API_KEY_SEND is not set
     * - transport falls back to general if HYVOR_RELAY_API_KEY_TRANSPORT is not set
     */
    'api_keys' => [
        'general' => $generalApiKey,
        'send' => env('HYVOR_RELAY_API_KEY_SEND', $generalApiKey),
        'transport' => env('HYVOR_RELAY_API_KEY_TRANSPORT', $generalApiKey),
    ],

    /**
     * Base URL of your Relay server (Hyvor-hosted or self-hosted).
     *
     * @see https://relay.hyvor.com/docs/api-console#api-usage
     *
     * Important:
     * - Use the base URL only (no trailing "/api/..." path).
     * - Example: "https://relay.hyvor.com"
     *
     * The transport will call:
     * - POST {endpoint}/api/console/sends
     */
    'endpoint' => env('HYVOR_RELAY_ENDPOINT', 'https://relay.hyvor.com'),

    /**
     * HTTP timeout in seconds for Console API requests.
     */
    'timeout' => (int) env('HYVOR_RELAY_TIMEOUT', 10),

    /**
     * HTTP connect timeout in seconds for Console API requests.
     */
    'connect_timeout' => (int) env('HYVOR_RELAY_CONNECT_TIMEOUT', 5),

    /**
     * Secret for validating incoming webhook signatures.
     *
     * @see https://relay.hyvor.com/docs/webhooks#validating-webhooks
     *
     * Relay signs the raw JSON body as HMAC-SHA256 and sends it in the X-Signature header.
     */
    'webhook_secret' => env('HYVOR_RELAY_WEBHOOK_SECRET'),

];
