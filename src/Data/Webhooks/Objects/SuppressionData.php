<?php

namespace Muensmedia\HyvorRelay\Data\Webhooks\Objects;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

class SuppressionData extends Data
{
    public function __construct(
        public int $id,
        #[MapInputName('created_at')]
        public int $createdAt,
        public string $email,
        public string $reason,
        public ?string $description,
    ) {}
}
