<?php

use App\Http\Controllers\MailController;

Route::get('list', [MailController::class, 'list']);
Route::post('acknowledge/{mailId}', [MailController::class, 'acknowledge']);
Route::get('mail/{mailId}', [MailController::class, 'mail']);
