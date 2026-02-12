<?php

namespace Muensmedia\HyvorRelay\Data\Webhooks\Payloads;

use Muensmedia\HyvorRelay\Data\Webhooks\Objects\SuppressionData;
use Spatie\LaravelData\Data;

/**
 * Payload DTO for suppression.created and suppression.deleted webhook events.
 *
 * @see https://relay.hyvor.com/docs/webhooks#suppression-created
 * @see https://relay.hyvor.com/docs/webhooks#suppression-deleted
 */
class SuppressionPayloadData extends Data
{
    public function __construct(
        public SuppressionData $suppression,
    ) {}
}
