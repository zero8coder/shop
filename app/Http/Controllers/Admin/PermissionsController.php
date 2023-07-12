<?php

namespace App\Http\Controllers\Admin;

use App\Filters\PermissionFilters;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PermissionRequest;
use App\Http\Resources\Admin\PermissionResource;
use App\Models\Permission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PermissionsController extends Controller
{
    public function index(Request $request, PermissionFilters $filters): JsonResponse
    {
        $permissions = Permission::latest()
            ->filter($filters)
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
