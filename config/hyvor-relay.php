<?php

return [
    'api_key' => env('HYVOR_RELAY_API_KEY', 'your-api-key-here'),
    // Base URL of your Relay server (Hyvor-hosted or self-hosted).
    'endpoint' => env('HYVOR_RELAY_ENDPOINT', 'https://mail5.relay.beyond-phishing.de'),
];
