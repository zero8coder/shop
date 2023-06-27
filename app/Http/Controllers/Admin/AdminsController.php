<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminRequest;
use App\Http\Requests\Admin\UpdateAdminRequest;
use App\Http\Resources\Admin\AdminResource;
use App\Models\Admin;
use Symfony\Component\HttpFoundation\Response;

class AdminsController extends Controller
{
    public function store(AdminRequest $request)
    {
        $admin = Admin::create([
            'name' => $request->input('name'),
            'password' => $request->input('password'),
            'phone' => $request->input('phone'),
            'sex' => $request->input('sex'),
            'email' => $request->input('email')
        ]);

        return $this->success(new AdminResource($admin));
    }

    public function update(Admin $admin, UpdateAdminRequest $request)
    {
        $admin->fill($request->only(['phone', 'email', 'sex']))->update();
        return $this->success(new AdminResource($admin));
    }

    public function destroy(Admin $admin)
    {
        $admin->delete();
        return $this->success([], '删除成功');
    }

}
