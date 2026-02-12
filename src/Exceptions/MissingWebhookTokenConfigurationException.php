<?php

namespace Muensmedia\HyvorRelay\Exceptions;

use RuntimeException;

class MissingWebhookTokenConfigurationException extends RuntimeException
{
    public static function make(): self
    {
        return new self(
            'Webhook token is not configured. Please set HYVOR_RELAY_WEBHOOK_TOKEN.'
        );
    }
}
