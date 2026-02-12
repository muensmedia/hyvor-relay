<?php

namespace Muensmedia\HyvorRelay\Data\Webhooks\Objects;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

class DkimResultData extends Data
{
    public function __construct(
        public bool $verified,
        #[MapInputName('checked_at')]
        public int $checkedAt,
        #[MapInputName('error_message')]
        public ?string $errorMessage,
    ) {}
}
