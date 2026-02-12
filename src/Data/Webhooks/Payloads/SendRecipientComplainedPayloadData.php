<?php

namespace Muensmedia\HyvorRelay\Data\Webhooks\Payloads;

use Muensmedia\HyvorRelay\Data\Webhooks\Objects\ComplaintData;
use Muensmedia\HyvorRelay\Data\Webhooks\Objects\SendData;
use Muensmedia\HyvorRelay\Data\Webhooks\Objects\SendRecipientData;
use Spatie\LaravelData\Data;

class SendRecipientComplainedPayloadData extends Data
{
    public function __construct(
        public SendData $send,
        public SendRecipientData $recipient,
        public ComplaintData $complaint,
    ) {}
}
