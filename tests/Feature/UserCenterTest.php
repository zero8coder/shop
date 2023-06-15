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

    // 看用户信息
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

    // 看自己的用户信息
    public function test_see_my_user_detail()
    {
        // 登录用户
        $this->signIn();
        $response = $this->get(route('api.v1.user.show'));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'email_verified_at',
                'created_at',
                'updated_at',
                'bound_phone',
                'bound_wechat'
            ]
        ]);
    }


}
