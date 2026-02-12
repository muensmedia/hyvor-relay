<?php

namespace Muensmedia\HyvorRelay\Data\Webhooks\Objects;

use Spatie\LaravelData\Data;

/**
 * DTO for bounce details included in bounced webhook payloads.
 *
 * @see https://relay.hyvor.com/docs/api-console#bounce-object
 */
class BounceData extends Data
{
    public function __construct(
        public string $text,
        public string $status,
    ) {}
}
