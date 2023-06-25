<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminRequest;
use App\Http\Requests\Admin\UpdateAdminRequest;
use App\Http\Resources\Admin\AdminResource;
use App\Models\Admin;

class AdminsController extends Controller
{
    public function store(AdminRequest $request)
    {
        $admin = Admin::create([
            'name' => $request->input('name'),
            'password' => $request->input('password'),
            'phone' => $request->input('phone'),
            'sex' => $request->input('sex')
        ]);

        return $this->success(new AdminResource($admin));
    }

    public function update(Admin $admin, UpdateAdminRequest $request)
    {
        $admin->fill($request->only(['email', 'sex']))->update();
        return $this->success(new AdminResource($admin));
    }

}
