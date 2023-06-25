<?php

namespace Tests\Feature;

use App\Models\Admin;
use Tests\TestCase;

class UpdateAdminTest extends TestCase
{

    public function test_update_admin()
    {
        $this->signInAdmin();
        $admin = Admin::factory()->create(['email' => '812419392@qq.com', 'sex' => Admin::SEX_MAN]);
        $admin->email = '812419393@qq.com';
        $admin->sex = Admin::SEX_WOMAN;
        $this->patch(route('admin.v1.admins.update', ['admin' => $admin->id]), $admin->toArray());
        tap($admin->fresh(), function ($admin) {
            $this->assertEquals('812419393@qq.com', $admin->email);
            $this->assertEquals(Admin::SEX_WOMAN, $admin->sex);
        });
    }
}
