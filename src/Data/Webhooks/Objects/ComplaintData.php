<?php

namespace Muensmedia\HyvorRelay\Data\Webhooks\Objects;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

/**
 * DTO for spam complaint/FBL details in complaint webhook payloads.
 *
 * @see https://relay.hyvor.com/docs/api-console#complaint-object
 */
class ComplaintData extends Data
{
    public function __construct(
        public string $text,
        #[MapInputName('feedback_type')]
        public string $feedbackType,
    ) {}
}
