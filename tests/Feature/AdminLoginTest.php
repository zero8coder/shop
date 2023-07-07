<?php

namespace Tests\Feature;

use App\Models\Admin;
use Tests\TestCase;

class AdminLoginTest extends TestCase
{
    public function test_admin_login_by_password()
    {
        $client = $this->createAdminPasswordClient();
        $admin = Admin::factory()->create();

        $response = $this->post(route('admin.v1.admins.login'), [
            'grant_type'    => 'password',
            'client_id'     => $client->id,
            'client_secret' => $client->secret,
            'username'      => $admin->name,
            'password'      => 'password',
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'data' => [
                'token_type',
                'expires_in',
                'access_token',
                'refresh_token'
            ]
        ]);
    }
}
