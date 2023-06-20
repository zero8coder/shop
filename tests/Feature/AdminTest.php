<?php

namespace Tests\Feature;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminTest extends TestCase
{
    public function test_add_admin()
    {
        $data = [
            'name' => 'libai',
            'password' => '123456',
            'phone' => '13160675344',
            'sex' => '1',
        ];
        $response = $this->post(route('admin.v1.admins.store'), $data);
        $response->assertStatus(200);
        $response->assertsee($data['name']);
    }
}
