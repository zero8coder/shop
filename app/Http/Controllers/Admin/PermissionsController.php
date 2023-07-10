<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PermissionRequest;
use App\Http\Resources\Admin\PermissionResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\Permission\Models\Permission;
use Symfony\Component\HttpFoundation\Response;

class PermissionsController extends Controller
{
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $permissions = Permission::latest()
            ->when($name = $request->input('name'), function ($query) use ($name) {
                return $query->where('name', $name);
            })
            ->paginate($request->input('perPage', 15));
        return $this->success(PermissionResource::collection($permissions));
    }


    public function store(Request $request)
    {
        $permission = Permission::create(['name' => $request->input('name')]);
        return $this->success(new PermissionResource($permission), '创建成功', Response::HTTP_CREATED);
    }

    public function edit(Permission $permission)
    {
        return $this->success(new PermissionResource($permission));
    }

    public function update(PermissionRequest $request, Permission $permission)
    {
        $permission->name = $request->input('name');
        $permission->update();
        return $this->success(new PermissionResource($permission));
    }


    public function destroy(Permission $permission)
    {
        $permission->delete();
        return $this->success();
    }
}
