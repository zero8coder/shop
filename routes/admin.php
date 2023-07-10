<?php

use App\Http\Controllers\Admin\AdminsController;
use App\Http\Controllers\AuthorizationsController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')
    ->namespace('Admin')
    ->name('admin.v1.')
    ->middleware('cors')
    ->group(function () {
        // 登录
        Route::post('login', [AuthorizationsController::class, 'store'])
            ->name('admins.login');

        Route::middleware('auth:admin')->group(function () {

            // 后台人员展示自己信息
            Route::get('admins/me', [AdminsController::class, 'me'])
                ->name('admins.me');

            // 后台人员导出任务
            Route::post('admins/exportTask', [AdminsController::class, 'addExportTask'])
                ->name('admins.exportTask');

            // 后台人员
            Route::resource('admins', 'AdminsController');

            // 权限
            Route::resource('permissions', 'PermissionsController')->only([
                'index',
                'store',
                'update',
                'destroy'
            ]);


        });

    });
