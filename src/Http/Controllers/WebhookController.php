<?php

namespace Muensmedia\HyvorRelay\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Muensmedia\HyvorRelay\Data\Webhooks\Payloads\DomainPayloadData;
use Muensmedia\HyvorRelay\Data\Webhooks\Payloads\DomainStatusChangedPayloadData;
use Muensmedia\HyvorRelay\Data\Webhooks\Payloads\SendRecipientAttemptPayloadData;
use Muensmedia\HyvorRelay\Data\Webhooks\Payloads\SendRecipientBouncedPayloadData;
use Muensmedia\HyvorRelay\Data\Webhooks\Payloads\SendRecipientComplainedPayloadData;
use Muensmedia\HyvorRelay\Data\Webhooks\Payloads\SendRecipientSuppressedPayloadData;
use Muensmedia\HyvorRelay\Data\Webhooks\Payloads\SuppressionPayloadData;
use Muensmedia\HyvorRelay\Enum\EventTypes;
use Muensmedia\HyvorRelay\Events\Webhooks\DomainCreatedReceived;
use Muensmedia\HyvorRelay\Events\Webhooks\DomainDeletedReceived;
use Muensmedia\HyvorRelay\Events\Webhooks\DomainStatusChangedReceived;
use Muensmedia\HyvorRelay\Events\Webhooks\SendRecipientAcceptedReceived;
use Muensmedia\HyvorRelay\Events\Webhooks\SendRecipientBouncedReceived;
use Muensmedia\HyvorRelay\Events\Webhooks\SendRecipientComplainedReceived;
use Muensmedia\HyvorRelay\Events\Webhooks\SendRecipientDeferredReceived;
use Muensmedia\HyvorRelay\Events\Webhooks\SendRecipientFailedReceived;
use Muensmedia\HyvorRelay\Events\Webhooks\SendRecipientSuppressedReceived;
use Muensmedia\HyvorRelay\Events\Webhooks\SuppressionCreatedReceived;
use Muensmedia\HyvorRelay\Events\Webhooks\SuppressionDeletedReceived;

class WebhookController
{
    public function __invoke(Request $request): JsonResponse
    {
        $type = EventTypes::tryFrom((string) $request->json('event'));
        $payload = (array) $request->json('payload', []);

        if ($type === null) {
            return response()->json(status: 204);
        }

        match ($type) {
            EventTypes::SEND_RECIPIENT_ACCEPTED => SendRecipientAcceptedReceived::dispatch(
                SendRecipientAttemptPayloadData::from($payload)
            ),
            EventTypes::SEND_RECIPIENT_DEFERRED => SendRecipientDeferredReceived::dispatch(
                SendRecipientAttemptPayloadData::from($payload)
            ),
            EventTypes::SEND_RECIPIENT_BOUNCED => SendRecipientBouncedReceived::dispatch(
                SendRecipientBouncedPayloadData::from($payload)
            ),
            EventTypes::SEND_RECIPIENT_COMPLAINED => SendRecipientComplainedReceived::dispatch(
                SendRecipientComplainedPayloadData::from($payload)
            ),
            EventTypes::SEND_RECIPIENT_SUPPRESSED => SendRecipientSuppressedReceived::dispatch(
                SendRecipientSuppressedPayloadData::from($payload)
            ),
            EventTypes::SEND_RECIPIENT_FAILED => SendRecipientFailedReceived::dispatch(
                SendRecipientAttemptPayloadData::from($payload)
            ),
            EventTypes::DOMAIN_CREATED => DomainCreatedReceived::dispatch(
                DomainPayloadData::from($payload)
            ),
            EventTypes::DOMAIN_STATUS_CHANGED => DomainStatusChangedReceived::dispatch(
                DomainStatusChangedPayloadData::from($payload)
            ),
            EventTypes::DOMAIN_DELETED => DomainDeletedReceived::dispatch(
                DomainPayloadData::from($payload)
            ),
            EventTypes::SUPPRESSION_CREATED => SuppressionCreatedReceived::dispatch(
                SuppressionPayloadData::from($payload)
            ),
            EventTypes::SUPPRESSION_DELETED => SuppressionDeletedReceived::dispatch(
                SuppressionPayloadData::from($payload)
            ),
        };

        return response()->json(status: 204);
    }
}
