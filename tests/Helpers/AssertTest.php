<?php

use Illuminate\Support\Facades\Mail;
use Muensmedia\HyvorRelay\Tests\Mailables\TestHtmlMailable;
use Muensmedia\HyvorRelay\Transport\HyvorRelayApiTransport;

it('uses hyvor-relay transport', function () {
    $transport = Mail::mailer()->getSymfonyTransport();
    expect($transport)->toBeInstanceOf(HyvorRelayApiTransport::class);
});

it('should send mail without actually sending it (Mail fake)', function () {
    Mail::fake();

    $to = fake()->safeEmail();
    $from = fake()->safeEmail();
    //    $to = 'i.schlenther@muensmedia.de';
    //    $from = 'app@beyond-phishing.de';

    $mailable = new TestHtmlMailable('<html lang="de"><body>HTML String</body></html>', 'Plain String')
        ->to($to)
        ->subject('Test Mail')
        ->from($from);

    Mail::send($mailable);

    Mail::assertSent(TestHtmlMailable::class, function (TestHtmlMailable $mail) use ($to, $from) {
        return $mail->hasTo($to)
            && $mail->hasFrom($from)
            && $mail->subject === 'Test Mail'
            && $mail->htmlString === '<html lang="de"><body>HTML String</body></html>'
            && $mail->plainText === 'Plain String';
    });
});
