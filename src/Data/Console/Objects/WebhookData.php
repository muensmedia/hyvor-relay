<?php

namespace Muensmedia\HyvorRelay\Data\Console\Objects;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

class WebhookData extends Data
{
    public function __construct(
        public int $id,
        public string $url,
        public ?string $description,
        public array $events,
        public ?string $secret,
        #[MapInputName('created_at')]
        public ?int $createdAt = null,
    ) {}
}
