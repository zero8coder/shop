<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Spatie\Permission\Models\Permission;

class PermissionFactory extends Factory
{
    protected $model = Permission::class;


    public function definition()
    {
        return [
            'name' => 'permission' . rand(0, 99999),
            'guard_name' => 'admin'
        ];
    }
}
