<?php

namespace Muensmedia\HyvorRelay\Data\Console\Objects;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

class ApiKeyData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public array $scopes,
        public ?string $key,
        #[MapInputName('created_at')]
        public int $createdAt,
        #[MapInputName('is_enabled')]
        public bool $isEnabled,
        #[MapInputName('last_accessed_at')]
        public ?int $lastAccessedAt,
    ) {}
}
