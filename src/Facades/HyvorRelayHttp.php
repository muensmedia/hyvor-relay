<?php

namespace Muensmedia\HyvorRelay\Facades;

use Illuminate\Support\Facades\Http;
use Muensmedia\HyvorRelay\Support\Http\HyvorRelayHttpFactory;

class HyvorRelayHttp extends Http
{
    protected static function getFacadeAccessor(): string
    {
        return HyvorRelayHttpFactory::class;
    }
}
