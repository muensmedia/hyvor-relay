<?php

use Illuminate\Support\Facades\Route;

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
        Route::post('/webhook', 'SendController@store');
    });