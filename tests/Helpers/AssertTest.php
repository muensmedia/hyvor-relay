<?php

use Illuminate\Support\Facades\Mail;
use Muensmedia\HyvorRelay\Mailable\HyvorMailable;
use Muensmedia\HyvorRelay\Transport\HyvorRelayApiTransport;

it('should send mail with hyvor-relay transport', function () {
    config()->set('hyvor-relay.api_key', 'c21a00b2bc7396c7010576902075476b');
    config()->set('mail.default', 'hyvor');
    config()->set('mail.mailers.hyvor', [
        'transport' => 'hyvor-relay',
    ]);
    config()->set('mail.from.address', 'test@beyond-phishing.dev');
    config()->set('mail.from.name', 'Iven');

    $transport = Mail::mailer()->getSymfonyTransport();
    expect($transport)->toBeInstanceOf(HyvorRelayApiTransport::class);

    $mailable = Mail::send(
        new HyvorMailable('HTML String', 'Plain String')
            ->to('i.schlenther@muensmedia.de')
            ->subject('Test Mail')
            ->from('test@beyond-phishing.dev')
    );

    expect($mailable)->toBeInstanceOf(HyvorMailable::class);
});
