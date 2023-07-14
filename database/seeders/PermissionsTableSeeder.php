<?php

namespace Database\Seeders;

use App\Enums\PermissionEnum;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now()->toDateTimeString();
        collect(PermissionEnum::$permissionValue)->chunk(500)->each(function ($chunk) use ($now) {
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
