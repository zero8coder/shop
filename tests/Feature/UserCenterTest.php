<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class UserCenterTest extends TestCase
{
    protected $user;
    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();

    }

    public function test_read_user_detail()
    {
        $response = $this->get(route('api.v1.users.show', ['user' => $this->user->id]));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'avatar',
                'email_verified_at',
                'created_at',
                'updated_at',
                'bound_phone',
                'bound_wechat'
            ]
        ]);
    }

}
