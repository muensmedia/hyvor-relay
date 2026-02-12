<?php

use Illuminate\Support\Facades\Route;
use Muensmedia\HyvorRelay\Http\Controllers\WebhookController;
use Muensmedia\HyvorRelay\Http\Middleware\VerifyWebhookSignature;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('api/hyvor-relay/v1')
    ->withoutMiddleware('throttle:api')
    ->name('hyvor-relay.api.v1.')
    ->group(function () {
        Route::post('/webhook', WebhookController::class)
            ->middleware([VerifyWebhookSignature::class])
            ->name('webhook');
    });
