<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RoleTest extends TestCase
{
    public function test_unauthorized_role_create()
    {
        $response = $this->json('GET', route('admin.v1.roles.create'));
        $response->assertStatus(401);
    }

    public function test_role_create()
    {
        $this->signInAdmin();
        $response = $this->json('GET', route('admin.v1.roles.create'));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'code',
            'message',
            'status',
            'data' => [
                'permissions'
            ]
        ]);
    }

}
