<?php

use Illuminate\Support\Facades\Mail;
use Muensmedia\HyvorRelay\Mailable\HyvorMailable;
use Muensmedia\HyvorRelay\Transport\HyvorRelayApiTransport;

it('should send mail with hyvor-relay transport', function () {
    $transport = Mail::mailer()->getSymfonyTransport();
    expect($transport)->toBeInstanceOf(HyvorRelayApiTransport::class);

    $mailable = Mail::send(
        new HyvorMailable('<html><body>HTML String</body></html>', 'Plain String')
            ->to('i.schlenther@muensmedia.de')
            ->subject('Test Mail')
            ->from('test@beyond-phishing.dev')
    );

    expect($mailable)->toBeInstanceOf(HyvorMailable::class);
});
