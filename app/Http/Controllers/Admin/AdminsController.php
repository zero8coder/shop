<?php

namespace App\Http\Controllers\Admin;

use App\Filters\AdminFilters;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminRequest;
use App\Http\Requests\Admin\UpdateAdminRequest;
use App\Http\Resources\Admin\AdminResource;
use App\Jobs\ExportTaskJob;
use App\Models\Admin;
use App\Models\ExportTask;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminsController extends Controller
{
    public function store(AdminRequest $request): JsonResponse
    {
        $this->authorize('create', Admin::class);
        $admin = Admin::create([
            'name' => $request->input('name'),
            'password' => $request->input('password'),
            'phone' => $request->input('phone'),
            'sex' => $request->input('sex'),
            'email' => $request->input('email')
        ]);

        return $this->success(new AdminResource($admin));
    }

    public function update(Admin $admin, UpdateAdminRequest $request): JsonResponse
    {
        $this->authorize('update', $admin);
        $admin->fill($request->only(['phone', 'email', 'sex']))->update();
        return $this->success(new AdminResource($admin));
    }

    public function destroy(Admin $admin): JsonResponse
    {
        $this->authorize('delete', $admin);
        $admin->delete();
        return $this->success([], '删除成功');
    }

    /**
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(Request $request, AdminFilters $filters): JsonResponse
    {
        $this->authorize('viewAny', Admin::class);
        $perPage = $request->input('perPage', 15);
        $admins = Admin::latest()->filter($filters)->paginate($perPage);
        return $this->success(AdminResource::collection($admins));
    }

    public function addExportTask(Request $request): JsonResponse
    {
        $this->authorize('export', Admin::class);
        $exportTask = ExportTask::addTask('导出管理员' . ExportTask::exportFilesSuffix(), 'admin', $request->all());
        ExportTaskJob::dispatch($exportTask);
        return $this->success();
    }

    public function show(Admin $admin): JsonResponse
    {
        return $this->success(new AdminResource($admin));
    }

    public function me(): JsonResponse
    {
        return $this->success(new AdminResource(auth('admin')->user()));
    }
}
