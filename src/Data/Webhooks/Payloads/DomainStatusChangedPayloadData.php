<?php

namespace Muensmedia\HyvorRelay\Data\Webhooks\Payloads;

use Muensmedia\HyvorRelay\Data\Webhooks\Objects\DkimResultData;
use Muensmedia\HyvorRelay\Data\Webhooks\Objects\DomainData;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

/**
 * Payload DTO for domain.status.changed webhook events.
 *
 * @see https://relay.hyvor.com/docs/webhooks#domain-status-changed
 */
class DomainStatusChangedPayloadData extends Data
{
    public function __construct(
        public DomainData $domain,
        #[MapInputName('old_status')]
        public string $oldStatus,
        #[MapInputName('new_status')]
        public string $newStatus,
        #[MapInputName('dkim_result')]
        public DkimResultData $dkimResult,
    ) {}
}
