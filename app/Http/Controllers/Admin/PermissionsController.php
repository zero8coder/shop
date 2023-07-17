<?php

namespace App\Http\Controllers\Admin;

use App\Filters\PermissionFilters;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PermissionRequest;
use App\Http\Resources\Admin\PermissionResource;
use App\Jobs\ExportTaskJob;
use App\Models\ExportTask;
use App\Models\Permission;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PermissionsController extends Controller
{
    /**
     * @throws AuthorizationException
     */
    public function index(Request $request, PermissionFilters $filters): JsonResponse
    {
        $this->authorize('viewAny', Permission::class);
        $permissions = Permission::latest()
            ->filter($filters)
            ->paginate($request->input('perPage', 15));
        return $this->success(PermissionResource::collection($permissions));
    }


    /**
     * @throws AuthorizationException
     */
    public function store(Request $request): JsonResponse
    {
        $this->authorize('create', Permission::class);
        $permission = Permission::create(['name' => $request->input('name')]);
        return $this->success(new PermissionResource($permission), '创建成功', Response::HTTP_CREATED);
    }

    /**
     * @throws AuthorizationException
     */
    public function edit(Permission $permission): JsonResponse
    {
        $this->authorize('update', $permission);
        return $this->success(new PermissionResource($permission));
    }

    /**
     * @throws AuthorizationException
     */
    public function update(PermissionRequest $request, Permission $permission): JsonResponse
    {
        $this->authorize('update', $permission);
        $permission->name = $request->input('name');
        $permission->update();
        return $this->success(new PermissionResource($permission));
    }


    /**
     * @throws AuthorizationException
     */
    public function destroy(Permission $permission): JsonResponse
    {
        $this->authorize('delete', $permission);
        $permission->delete();
        return $this->success();
    }

    /**
     * @throws AuthorizationException
     */
    public function addExportTask(Request $request): JsonResponse
    {
        $this->authorize('export', Permission::class);
        $exportTask = ExportTask::addTask('导出权限' . ExportTask::exportFilesSuffix(), 'permission', $request->all());
        ExportTaskJob::dispatch($exportTask);
        return $this->success();
    }
}
