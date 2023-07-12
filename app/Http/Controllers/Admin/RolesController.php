<?php

namespace App\Http\Controllers\Admin;

use App\Filters\RoleFilters;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RoleRequest;
use App\Http\Resources\Admin\RoleResource;
use App\Jobs\ExportTaskJob;
use App\Models\ExportTask;
use App\Models\Permission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Role;

class RolesController extends Controller
{
    public function index(Request $request, RoleFilters $filters)
    {
        $roles = Role::latest()
            ->with('permissions')
            ->filter($filters)
            ->paginate($request->input('perPage', 15));
        return $this->success(RoleResource::collection($roles));
    }

    public function create(): JsonResponse
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

    public function edit(Role $role): JsonResponse
    {
        $role->load('permissions');
        return $this->success(new RoleResource($role));
    }

    public function update(RoleRequest $request, Role $role): JsonResponse
    {
        $role->name = $request->input('name');
        $role->syncPermissions($request->input('permissions'));
        $role->load('permissions');
        return $this->success(new RoleResource($role));

    }

    public function destroy(Role $role): JsonResponse
    {
        $role->delete();
        return $this->success();
    }

    public function addExportTask(Request $request): JsonResponse
    {
        $exportTask = ExportTask::addTask('导出角色' . ExportTask::exportFilesSuffix(), 'role', $request->all());
        ExportTaskJob::dispatch($exportTask);
        return $this->success();
    }
}
