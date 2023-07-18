<?php

namespace Database\Factories;

use App\Models\Menu;
use Illuminate\Database\Eloquent\Factories\Factory;

class MenuFactory extends Factory
{
    public function definition(): array
    {
        return [
            'pid' => 0,
            'name' => $this->faker->name,
            'url' => $this->faker->url,
            'sort' => Menu::max('sort') ?? 1
        ];
    }
}
