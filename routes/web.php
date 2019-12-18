<?php

use App\Http\Controllers\WebhookController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])
    ->group(function () {
        Route::get('/home', function () {
            return view('home');
        })->name('home');
        Route::get('webhook/{webhook}/enable', [WebhookController::class, 'enable'])->name('webhook.enable');
        Route::resource('webhook', WebhookController::class)->only(['store', 'destroy']);
    });
