<?php

use EscolaLms\YeppChat\Http\Controllers\YeppChatController;
use Illuminate\Support\Facades\Route;

Route::prefix('api/yepp-chat')
    ->middleware(['auth:api'])
    ->group(function () {
        Route::post('/{lessonId}', [YeppChatController::class, 'get']);
    });
