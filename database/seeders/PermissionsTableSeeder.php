<?php

namespace Database\Seeders;

use App\Enums\PermissionEnum;
use App\Models\Permission;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now()->toDateTimeString();
        $permissions = Permission::all(['name'])->pluck('name')->toArray();
        // 不在数据库的权限
        $permissionsNotInDB = array_diff(PermissionEnum::$permissionValue, $permissions);
        collect($permissionsNotInDB)->chunk(500)->each(function ($chunk) use ($now) {
            $data = [];
            $chunk->each(function ($item) use ($now, &$data) {
                $data[] =  [
                    'name'       => $item,
                    'guard_name' => 'admin',
                    'created_at' => $now,
                    'updated_at' => $now
                ];
            });
            DB::table('permissions')->insert($data);
        });
    }
}
