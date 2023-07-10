<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RoleRequest;
use App\Http\Resources\Admin\RoleResource;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesController extends Controller
{
    public function index(Request $request)
    {
        $roles = Role::latest()
            ->when($name = $request->input('name'), function ($query) use ($name) {
                return $query->where('name', $name);
            })
            ->paginate($request->input('perPage', 15));
        return $this->success(RoleResource::collection($roles));
    }

    public function create(): \Illuminate\Http\JsonResponse
    {
        $permissions = Permission::all(['name', 'id']);
        return $this->success(['permissions' => $permissions]);
    }

    public function store(RoleRequest $request)
    {
        $permissions = $request->input('permissions');
        $role =  Role::create(['name' => $request->input('name')]);
        if (!empty($permissions)) {
            $role->syncPermissions($permissions);
        }
        $role->load('permissions');

        return $this->success(new RoleResource($role));
    }

    public function edit(Role $role): \Illuminate\Http\JsonResponse
    {
        $role->load('permissions');
        return $this->success(new RoleResource($role));
    }

    public function update(RoleRequest $request, Role $role): \Illuminate\Http\JsonResponse
    {
        $role->name = $request->input('name');
        $role->syncPermissions($request->input('permissions'));
        $role->load('permissions');
        return $this->success(new RoleResource($role));

    }

    public function destroy(Role $role): \Illuminate\Http\JsonResponse
    {
        $role->delete();
        return $this->success();
    }
}
