<?php

namespace Muensmedia\HyvorRelay\Actions\Console\Sends;

use Lorisleiva\Actions\Concerns\AsObject;
use Muensmedia\HyvorRelay\Actions\Console\Concerns\InteractsWithConsoleApi;
use Muensmedia\HyvorRelay\Data\Console\Requests\SendEmailPayloadData;
use Muensmedia\HyvorRelay\Data\Console\Responses\SendEmailResponseData;

/**
 * @see https://relay.hyvor.com/docs/api-console#send-email
 */
class SendEmailAction
{
    use AsObject, InteractsWithConsoleApi;

    public function handle(SendEmailPayloadData $payload, ?string $idempotencyKey = null): SendEmailResponseData
    {
        $headers = [];

        if ($idempotencyKey !== null && $idempotencyKey !== '') {
            $headers['X-Idempotency-Key'] = $idempotencyKey;
        }

        return SendEmailResponseData::from(
            $this->request(
                'POST',
                'sends',
                json: $payload->toApiPayload(),
                headers: $headers,
                apiKeyConfig: 'hyvor-relay.api_keys.send'
            )
        );
    }
}
