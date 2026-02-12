<?php

namespace Muensmedia\HyvorRelay\Data\Webhooks\Objects;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

/**
 * DTO for domain data managed by Hyvor Relay.
 *
 * @see https://relay.hyvor.com/docs/api-console#domain-object
 */
class DomainData extends Data
{
    public function __construct(
        public int $id,
        #[MapInputName('created_at')]
        public int $createdAt,
        public string $domain,
        public string $status,
        #[MapInputName('dkim_selector')]
        public string $dkimSelector,
        #[MapInputName('dkim_host')]
        public string $dkimHost,
        #[MapInputName('dkim_public_key')]
        public string $dkimPublicKey,
        #[MapInputName('dkim_txt_value')]
        public string $dkimTxtValue,
        #[MapInputName('dkim_checked_at')]
        public ?int $dkimCheckedAt,
        #[MapInputName('dkim_error_message')]
        public ?string $dkimErrorMessage,
    ) {}
}
