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
        $this->authorize('viewAny', Role::class);
        $roles = Role::latest()
            ->with('permissions')
            ->filter($filters)
            ->paginate($request->input('perPage', 15));
        return $this->success(RoleResource::collection($roles));
    }

    public function create(): JsonResponse
    {
        $this->authorize('create', Role::class);
        $permissions = Permission::all(['name', 'id']);
        return $this->success(['permissions' => $permissions]);
    }

    public function store(RoleRequest $request)
    {
        $this->authorize('create', Role::class);
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
        $this->authorize('update', $role);
        $role->load('permissions');
        return $this->success(new RoleResource($role));
    }

    public function update(RoleRequest $request, Role $role): JsonResponse
    {
        $this->authorize('update', $role);
        $role->name = $request->input('name');
        $role->syncPermissions($request->input('permissions'));
        $role->load('permissions');
        return $this->success(new RoleResource($role));

    }

    public function destroy(Role $role): JsonResponse
    {
        $this->authorize('delete', $role);
        $role->delete();
        return $this->success();
    }

    public function addExportTask(Request $request): JsonResponse
    {
        $this->authorize('export', Role::class);
        $exportTask = ExportTask::addTask('导出角色' . ExportTask::exportFilesSuffix(), 'role', $request->all());
        ExportTaskJob::dispatch($exportTask);
        return $this->success();
    }
}
