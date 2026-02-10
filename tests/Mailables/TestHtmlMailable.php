<?php

namespace Muensmedia\HyvorRelay\Tests\Mailables;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;

final class TestHtmlMailable extends Mailable
{
    use Queueable;

    public function __construct(
        public string $htmlString,
        public string $plainText = '',
    ) {
        //
    }

    public function content(): Content
    {
        return new Content(
            htmlString: $this->htmlString,
        );
    }
}
