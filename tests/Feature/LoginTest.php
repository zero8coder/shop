<?php

namespace Tests\Feature;

use App\Models\User;
use DB;
use Tests\TestCase;

class LoginTest extends TestCase
{
    public function test_get_token_by_password()
    {
        $client = $this->createClient();
        $user = User::factory()->create();

        $response = $this->post('/oauth/token', [
            'grant_type' => 'password',
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'username' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'token_type',
            'expires_in',
            'access_token',
            'refresh_token'
        ]);
    }
}
