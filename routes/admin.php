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
            // 添加后台人员
            Route::post('admins', [AdminsController::class, 'store'])
                ->name('admins.store');

            // 修改后台人员
            Route::patch('admins/{admin}', [AdminsController::class, 'update'])
                ->name('admins.update');

            // 删除后台人员
            Route::delete('admins/{admin}', [AdminsController::class, 'destroy'])
                ->name('admins.del');

            // 后台人员列表
            Route::get('admins/index', [AdminsController::class, 'index'])
                ->name('admins.index');

            // 后台人员导出任务
            Route::post('admins/exportTask', [AdminsController::class, 'addExportTask'])
                ->name('admins.exportTask');
        });

    });
