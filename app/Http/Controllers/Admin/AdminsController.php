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
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminsController extends Controller
{
    public function store(AdminRequest $request): JsonResponse
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

    public function update(Admin $admin, UpdateAdminRequest $request): JsonResponse
    {
        $admin->fill($request->only(['phone', 'email', 'sex']))->update();
        return $this->success(new AdminResource($admin));
    }

    public function destroy(Admin $admin): JsonResponse
    {
        $admin->delete();
        return $this->success([], '删除成功');
    }

    public function index(Request $request, AdminFilters $filters): JsonResponse
    {
        $perPage = $request->input('perPage', 15);
        $admins = Admin::latest()->filter($filters)->paginate($perPage);
        return $this->success($this->formatPaginatorData(AdminResource::collection($admins)));
    }

    public function addExportTask(Request $request): JsonResponse
    {
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
