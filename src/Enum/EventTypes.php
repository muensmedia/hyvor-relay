<?php

namespace Muensmedia\HyvorRelay\Enum;

/**
 * Event types for Hyvor Relay webhooks.
 * @see https://relay.hyvor.com/docs/webhooks#events
 */
enum EventTypes: string
{
    case SEND_RECIPIENT_ACCEPTED = 'send.recipient.accepted';
    case SEND_RECIPIENT_DEFERRED = 'send.recipient.deferred';
    case SEND_RECIPIENT_BOUNCED = 'send.recipient.bounced';
    case SEND_RECIPIENT_COMPLAINED = 'send.recipient.complained';
    case SEND_RECIPIENT_FAILED = 'send.recipient.failed';

    case DOMAIN_CREATED = 'domain.created';
    case DOMAIN_STATUS_CHANGED = 'domain.status.changed';
    case DOMAIN_DELETED = 'domain.deleted';

    case SUPPRESSION_CREATED = 'suppression.created';
    case SUPPRESSION_DELETED = 'suppression.deleted';
}
