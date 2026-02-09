<?php

use Illuminate\Support\Facades\Mail;
use Mailable\HyvorMailable;

it('should send mail with hyvor-relay transport', function () {
    Mail::send(new HyvorMailable(
        'Test HTML',
        'Test Plain Text'
    )->to('i.schlenther@muensmedia.de', 'Iven Schlenther')->subject('Test Subject'));
});
