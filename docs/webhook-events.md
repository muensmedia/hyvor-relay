# Hyvor Relay Webhook Events (Laravel)

This document lists all webhook events dispatched by this package as Laravel events.

## Event Overview

| Hyvor Event | Laravel Event Class | Short Description | Docs |
|---|---|---|---|
| `send.recipient.accepted` | `Muensmedia\HyvorRelay\Events\Webhooks\SendRecipientAcceptedReceived` | Recipient SMTP server accepted the message. | https://relay.hyvor.com/docs/webhooks#send-recipient-accepted |
| `send.recipient.deferred` | `Muensmedia\HyvorRelay\Events\Webhooks\SendRecipientDeferredReceived` | Delivery was temporarily deferred and will be retried. | https://relay.hyvor.com/docs/webhooks#send-recipient-deferred |
| `send.recipient.bounced` | `Muensmedia\HyvorRelay\Events\Webhooks\SendRecipientBouncedReceived` | Delivery was rejected (bounce). | https://relay.hyvor.com/docs/webhooks#send-recipient-bounced |
| `send.recipient.complained` | `Muensmedia\HyvorRelay\Events\Webhooks\SendRecipientComplainedReceived` | Recipient submitted a spam complaint. | https://relay.hyvor.com/docs/webhooks#send-recipient-complained |
| `send.recipient.suppressed` | `Muensmedia\HyvorRelay\Events\Webhooks\SendRecipientSuppressedReceived` | Delivery was skipped due to suppression. | https://relay.hyvor.com/docs/webhooks#send-recipient-suppressed |
| `send.recipient.failed` | `Muensmedia\HyvorRelay\Events\Webhooks\SendRecipientFailedReceived` | Delivery failed after retries due to technical issues. | https://relay.hyvor.com/docs/webhooks#send-recipient-failed |
| `domain.created` | `Muensmedia\HyvorRelay\Events\Webhooks\DomainCreatedReceived` | A new domain was created. | https://relay.hyvor.com/docs/webhooks#domain-created |
| `domain.status.changed` | `Muensmedia\HyvorRelay\Events\Webhooks\DomainStatusChangedReceived` | Domain status changed. | https://relay.hyvor.com/docs/webhooks#domain-status-changed |
| `domain.deleted` | `Muensmedia\HyvorRelay\Events\Webhooks\DomainDeletedReceived` | Domain was deleted. | https://relay.hyvor.com/docs/webhooks#domain-deleted |
| `suppression.created` | `Muensmedia\HyvorRelay\Events\Webhooks\SuppressionCreatedReceived` | A suppression entry was created. | https://relay.hyvor.com/docs/webhooks#suppression-created |
| `suppression.deleted` | `Muensmedia\HyvorRelay\Events\Webhooks\SuppressionDeletedReceived` | A suppression entry was removed. | https://relay.hyvor.com/docs/webhooks#suppression-deleted |

## Laravel Event Implementation

For implementation details (listeners, discovery, and registration), see the official Laravel documentation:

https://laravel.com/docs/events
