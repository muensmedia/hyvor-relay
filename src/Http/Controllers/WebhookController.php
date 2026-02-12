<?php

namespace Muensmedia\HyvorRelay\Http\Controllers;


use Illuminate\Http\Request;

class WebhookController
{

    public function __invoke(Request $request)
    {
        return match ($request->json('event')) {

        }
    }
}