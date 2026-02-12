<?php

use Muensmedia\HyvorRelay\Data\Console\Requests\SendEmailAddressData;
use Muensmedia\HyvorRelay\Data\Console\Requests\SendEmailPayloadData;

it('normalizes send email payload dto to api payload format', function () {
    $payload = SendEmailPayloadData::from([
        'from' => [
            'email' => 'from@example.test',
            'name' => 'From Name',
        ],
        'to' => [
            'user-1@example.test',
            [
                'email' => 'user-2@example.test',
                'name' => 'User Two',
            ],
        ],
        'cc' => [
            'email' => 'cc@example.test',
            'name' => 'CC User',
        ],
        'subject' => 'Hello',
        'body_text' => 'Plain',
        'body_html' => '<p>Plain</p>',
        'headers' => ['X-Custom' => 'value'],
        'attachments' => [
            [
                'content' => 'SGVsbG8=',
                'name' => 'hello.txt',
                'content_type' => 'text/plain',
            ],
        ],
    ]);

    expect($payload->toApiPayload())->toBe([
        'from' => [
            'email' => 'from@example.test',
            'name' => 'From Name',
        ],
        'to' => [
            'user-1@example.test',
            [
                'email' => 'user-2@example.test',
                'name' => 'User Two',
            ],
        ],
        'cc' => [
            'email' => 'cc@example.test',
            'name' => 'CC User',
        ],
        'subject' => 'Hello',
        'body_html' => '<p>Plain</p>',
        'body_text' => 'Plain',
        'headers' => ['X-Custom' => 'value'],
        'attachments' => [[
            'content' => 'SGVsbG8=',
            'name' => 'hello.txt',
            'content_type' => 'text/plain',
        ]],
    ]);
});

it('supports passing typed address dto instances', function () {
    $payload = new SendEmailPayloadData(
        from: new SendEmailAddressData(email: 'from@example.test', name: 'From'),
        to: new SendEmailAddressData(email: 'to@example.test')
    );

    expect($payload->toApiPayload())->toBe([
        'from' => [
            'email' => 'from@example.test',
            'name' => 'From',
        ],
        'to' => 'to@example.test',
    ]);
});
