<?php

namespace App\Http\Controllers\Admin;

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

    public function index(Request $request): JsonResponse
    {
        $perPage = $request->input('perPage', 15);
        $admins = Admin::latest()
            ->when($name = $request->input('name'), function ($q) use ($name) {
                return $q->where('name', 'like', '%' . $name . '%');
            })
            ->when($phone = $request->input('phone'), function ($q) use ($phone) {
                return $q->where('phone', 'like', $phone . '%');
            })
            ->when($sex = $request->input('sex'), function ($q) use ($sex) {
                return $q->where('sex', $sex);
            })
            ->paginate($perPage);

        return $this->success($this->formatPaginatorData(AdminResource::collection($admins)));
    }

    public function addExportTask(Request $request): JsonResponse
    {
        $exportTask = ExportTask::addTask('导出管理员' . auth()->id() . Carbon::now()->toDateTimeString(), 'admin', $request->all());
        ExportTaskJob::dispatch($exportTask);
        return $this->success();
    }
}
