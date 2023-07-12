<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PermissionFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => 'permission' . rand(0, 99999),
            'guard_name' => 'admin'
        ];
    }
}
