<?php

namespace Muensmedia\HyvorRelay\Exceptions;

use Exception;
use Illuminate\Http\Client\Response;

class HyvorRelayApiException extends Exception
{
    public function __construct(
        public int $status,
        public string $method,
        public string $uri,
        public mixed $responseBody,
        public ?string $source = null,
    ) {
        $sourcePrefix = $source ? "[{$source}] " : '';
        parent::__construct("{$sourcePrefix}Hyvor Relay API request failed: {$method} {$uri} ({$status})");
    }

    public static function fromResponse(
        Response $response,
        string $method,
        string $uri,
        ?string $source = null
    ): self
    {
        return new self(
            status: $response->status(),
            method: $method,
            uri: $uri,
            responseBody: $response->json() ?? $response->body(),
            source: $source,
        );
    }
}
