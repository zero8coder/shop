<?php

namespace Tests\Feature;


use Tests\TestCase;

class LogoutAdminTest extends TestCase
{
    public function test_logout()
    {
        $this->signInAdmin();
        $response = $this->json('delete', route('admin.v1.admins.logout'));
        $response->assertStatus(204);
    }
}
