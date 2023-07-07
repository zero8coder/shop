<?php

namespace Tests\Feature;

use App\Models\Admin;
use Tests\TestCase;

class UpdateAdminTest extends TestCase
{

    public function test_update_admin()
    {
        $this->signInAdmin();
        $admin = Admin::factory()->create(['phone' => '13160675341','email' => '812419391@qq.com', 'sex' => Admin::SEX_MAN]);
        $admin->phone = '13160675342';
        $admin->email = '812419393@qq.com';
        $admin->sex = Admin::SEX_WOMAN;
        $this->json('patch', route('admin.v1.admins.update', ['admin' => $admin->id]), $admin->toArray());
        tap($admin->fresh(), function ($admin) {
            $this->assertEquals('13160675342', $admin->phone);
            $this->assertEquals('812419393@qq.com', $admin->email);
            $this->assertEquals(Admin::SEX_WOMAN, $admin->sex);
        });
    }
}
