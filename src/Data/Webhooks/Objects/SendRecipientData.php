<?php

namespace Muensmedia\HyvorRelay\Data\Webhooks\Objects;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

/**
 * DTO for a recipient entry of a send.
 *
 * @see https://relay.hyvor.com/docs/api-console#send-recipient-object
 */
class SendRecipientData extends Data
{
    public function __construct(
        public int $id,
        public string $type,
        public string $address,
        public ?string $name,
        public string $status,
        #[MapInputName('try_count')]
        public int $tryCount,
    ) {}
}
