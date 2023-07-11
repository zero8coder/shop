<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => 'role' . rand(0, 9999),
            'guard_name' => 'admin'
        ];
    }
}
