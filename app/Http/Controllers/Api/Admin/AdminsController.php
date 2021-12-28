<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\AdminRequest;
use App\Http\Resources\Admin\AdminResource;
use App\Models\Admin;

class AdminsController extends Controller
{
    public function store(AdminRequest $request): AdminResource
    {
        $admin = Admin::create([
            'name' => $request->input('name'),
            'password' => $request->input('password'),
            'phone' => $request->input('phone'),
            'sex' => $request->input('sex')
        ]);

        return new AdminResource($admin);
    }
}
