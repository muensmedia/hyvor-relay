<?php

namespace Muensmedia\HyvorRelay\Data\Console\Responses;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

class SendEmailResponseData extends Data
{
    public function __construct(
        public int $id,
        #[MapInputName('message_id')]
        public string $messageId,
    ) {}
}
