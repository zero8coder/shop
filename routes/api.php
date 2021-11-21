<?php

use App\Http\Controllers\Api\UsersController;
use App\Http\Controllers\Api\VerificationCodesController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')
    ->namespace('Api')
    ->name('api.v1.')
    ->group(function () {
        Route::middleware('throttle:' . config('api.rate_limits.sign'))
            ->group(function () {
                // 短信验证码
                Route::post('verificationCodes', [VerificationCodesController::class, 'store'])
                    ->name('verificationCodes.store');

                // 注册
                Route::post('users', [UsersController::class, 'store'])
                    ->name('users.store');
            });
        Route::middleware('throttle:' . config('api.rate_limits.access'))
            ->group(function () {

            });
    });
