<?php

namespace Muensmedia\HyvorRelay\Data\Webhooks\Objects;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

class ComplaintData extends Data
{
    public function __construct(
        public string $text,
        #[MapInputName('feedback_type')]
        public string $feedbackType,
    ) {}
}
