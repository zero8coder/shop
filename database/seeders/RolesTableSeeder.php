<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\Role;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{

    public function run()
    {
        $now = Carbon::now()->toDateTimeString();
        $roles = Role::all(['name'])->pluck('name')->toArray();
        // 不在数据库的权限
        $rolesNotInDB = array_diff(RoleEnum::$roleValue, $roles);
        collect($rolesNotInDB)->chunk(500)->each(function ($chunk) use ($now) {
            $data = [];
            $chunk->each(function ($item) use ($now, &$data) {
                $data[] =  [
                    'name'       => $item,
                    'guard_name' => 'admin',
                    'created_at' => $now,
                    'updated_at' => $now
                ];
            });
            DB::table('roles')->insert($data);
        });
    }
}
