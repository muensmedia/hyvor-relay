<?php

namespace Muensmedia\HyvorRelay\Mailable;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;

class HyvorMailable extends Mailable
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