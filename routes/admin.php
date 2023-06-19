<?php

use App\Http\Controllers\Admin\AdminsController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')
    ->namespace('Admin')
    ->name('admin.v1.')
    ->group(function () {
        // 添加后台人员
        Route::post('admins', [AdminsController::class, 'store'])
            ->name('admins.store');
    });
