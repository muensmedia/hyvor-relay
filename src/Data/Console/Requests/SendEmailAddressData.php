<?php

namespace Muensmedia\HyvorRelay\Data\Console\Requests;

use Spatie\LaravelData\Data;

class SendEmailAddressData extends Data
{
    public function __construct(
        public string $email,
        public ?string $name = null,
    ) {}

    /**
     * @return array{email: string, name?: string}|string
     */
    public function toApiPayload(): array|string
    {
        if ($this->name === null || $this->name === '') {
            return $this->email;
        }

        return [
            'email' => $this->email,
            'name' => $this->name,
        ];
    }
}
