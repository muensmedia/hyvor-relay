<?php

use Illuminate\Support\Facades\Mail;
use Muensmedia\HyvorRelay\Mailable\HyvorMailable;
use Muensmedia\HyvorRelay\Transport\HyvorRelayApiTransport;

it('uses hyvor-relay transport', function () {
    $transport = Mail::mailer()->getSymfonyTransport();
    expect($transport)->toBeInstanceOf(HyvorRelayApiTransport::class);
});

it('should send mail without actually sending it (Mail fake)', function () {
    Mail::fake();

    $mailable = new HyvorMailable('<html lang="de"><body>HTML String</body></html>', 'Plain String')
        ->to('i.schlenther@muensmedia.de')
        ->subject('Test Mail')
        ->from('test@beyond-phishing.dev');

    Mail::send($mailable);

    Mail::assertSent(HyvorMailable::class, function (HyvorMailable $mail) {
        return $mail->hasTo('i.schlenther@muensmedia.de')
            && $mail->hasFrom('test@beyond-phishing.dev')
            && $mail->subject === 'Test Mail'
            && $mail->htmlString === '<html lang="de"><body>HTML String</body></html>'
            && $mail->plainText === 'Plain String';
    });
});
