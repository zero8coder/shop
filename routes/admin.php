<?php

use App\Http\Controllers\Admin\AdminsController;
use App\Http\Controllers\AuthorizationsController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')
    ->namespace('Admin')
    ->name('admin.v1.')
    ->group(function () {
        // 登录
        Route::post('login', [AuthorizationsController::class, 'store'])
            ->name('admins.login');

        Route::middleware('auth:admin')->group(function () {
            // 添加后台人员
            Route::post('admins', [AdminsController::class, 'store'])
                ->name('admins.store');

            // 修改后台人员
            Route::patch('admins/{admin}', [AdminsController::class, 'update'])
                ->name('admins.update');
        });

    });
