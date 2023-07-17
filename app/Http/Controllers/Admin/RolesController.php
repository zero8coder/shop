<?php

namespace App\Http\Controllers\Admin;

use App\Filters\RoleFilters;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RoleRequest;
use App\Http\Resources\Admin\RoleResource;
use App\Jobs\ExportTaskJob;
use App\Models\ExportTask;
use App\Models\Permission;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Role;

class RolesController extends Controller
{
    /**
     * @throws AuthorizationException
     */
    public function index(Request $request, RoleFilters $filters): JsonResponse
    {
        $this->authorize('viewAny', Role::class);
        $roles = Role::latest()
            ->with('permissions')
            ->filter($filters)
            ->paginate($request->input('perPage', 15));
        return $this->success(RoleResource::collection($roles));
    }

    /**
     * @throws AuthorizationException
     */
    public function create(): JsonResponse
    {
        $this->authorize('create', Role::class);
        $permissions = Permission::all(['name', 'id']);
        return $this->success(['permissions' => $permissions]);
    }

    /**
     * @throws AuthorizationException
     */
    public function store(RoleRequest $request): JsonResponse
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

    /**
     * @throws AuthorizationException
     */
    public function edit(Role $role): JsonResponse
    {
        $this->authorize('update', $role);
        $role->load('permissions');
        return $this->success(new RoleResource($role));
    }

    /**
     * @throws AuthorizationException
     */
    public function update(RoleRequest $request, Role $role): JsonResponse
    {
        $this->authorize('update', $role);
        $role->name = $request->input('name');
        $role->syncPermissions($request->input('permissions'));
        $role->load('permissions');
        return $this->success(new RoleResource($role));

    }

    /**
     * @throws AuthorizationException
     */
    public function destroy(Role $role): JsonResponse
    {
        $this->authorize('delete', $role);
        $role->delete();
        return $this->success();
    }

    /**
     * @throws AuthorizationException
     */
    public function addExportTask(Request $request): JsonResponse
    {
        $this->authorize('export', Role::class);
        $exportTask = ExportTask::addTask('导出角色' . ExportTask::exportFilesSuffix(), 'role', $request->all());
        ExportTaskJob::dispatch($exportTask);
        return $this->success();
    }
}
