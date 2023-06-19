<?php

use App\Http\Controllers\Api\Admin\AdminsController;
use App\Http\Controllers\Api\AuthorizationsController;
use App\Http\Controllers\Api\CaptchasController;
use App\Http\Controllers\Api\ImagesController;
use App\Http\Controllers\Api\TestController;
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

                // 测试
                Route::post('test', [TestController::class, 'test'])
                    ->name('test.test');
            });
        Route::middleware('throttle:' . config('api.rate_limits.access'))
            ->group(function () {

                // 某个用户的详情
                Route::get('users/{user}', [UsersController::class, 'show'])
                    ->name('users.show');

                // 登录后可以访问的接口
                Route::middleware('auth:api')->group(function () {
                    // 当前登录用户信息
                    Route::get('user', [UsersController::class, 'me'])->name('user.show');
                    // 编辑登录用户信息
                    Route::patch('user', [UsersController::class, 'update'])->name('user.update');
                    // 上传图片
                    Route::post('images', [ImagesController::class, 'store'])->name('images.store');
                });
            });
    });
