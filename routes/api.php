<?php

use App\Http\Controllers\Api\AuthorizationsController;
use App\Http\Controllers\Api\CaptchasController;
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

                // 登录
                Route::post('authorizations', [AuthorizationsController::class, 'store'])
                    ->name('authorizations.store');
                // 刷新token
                Route::put('authorizations/current', [AuthorizationsController::class, 'update'])
                    ->name('authorizations.update');
                // 删除token
                Route::delete('authorizations/current', [AuthorizationsController::class, 'destroy'])
                    ->name('authorizations.destroy');

                // 图片验证码
                Route::post('captchas', [CaptchasController::class, 'store'])
                    ->name('captchas.store');
            });
        Route::middleware('throttle:' . config('api.rate_limits.access'))
            ->group(function () {
            });
    });
