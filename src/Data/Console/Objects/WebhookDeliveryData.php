<?php

namespace Muensmedia\HyvorRelay\Data\Console\Objects;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

class WebhookDeliveryData extends Data
{
    public function __construct(
        public int $id,
        #[MapInputName('created_at')]
        public int $createdAt,
        public string $url,
        public string $event,
        public string $status,
        public ?string $response,
    ) {}
}
