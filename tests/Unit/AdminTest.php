<?php

namespace Tests\Unit;


use App\Models\Admin;
use Tests\TestCase;

class AdminTest extends TestCase
{
    public function test_get_sex_name()
    {
        $admin = Admin::factory()->create(['sex' => Admin::SEX_MAN]);
        $this->assertEquals('男', $admin->sex_name);
    }
}
