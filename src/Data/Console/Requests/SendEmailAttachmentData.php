<?php

namespace Muensmedia\HyvorRelay\Data\Console\Requests;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

class SendEmailAttachmentData extends Data
{
    public function __construct(
        public string $content,
        public ?string $name = null,
        #[MapInputName('content_type')]
        public ?string $contentType = null,
    ) {}

    /**
     * @return array{content: string, name?: string, content_type?: string}
     */
    public function toApiPayload(): array
    {
        return array_filter([
            'content' => $this->content,
            'name' => $this->name,
            'content_type' => $this->contentType,
        ], static fn (mixed $value): bool => $value !== null);
    }
}
