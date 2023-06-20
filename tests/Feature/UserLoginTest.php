<?php

namespace Tests\Feature;

use App\Models\User;
use Cache;
use Tests\TestCase;

class UserLoginTest extends TestCase
{
    // 密码方式获取token
    public function test_login_by_password()
    {
        $client = $this->createUserPasswordClient();
        $user = User::factory()->create();

        $response = $this->post(route('api.v1.authorizations.store'), [
            'grant_type' => 'password',
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'username' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'token_type',
            'expires_in',
            'access_token',
            'refresh_token'
        ]);
    }

    // 图片验证码
    public function test_pic_code()
    {
        $response = $this->post(route('api.v1.captchas.store'), ['phone' => '13160675344']);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'captcha_key',
                'expired_at',
                'captcha_image_content'
            ]
        ]);
    }

    // 注册用户
    public function test_register_user()
    {
        $response = $this->post(route('api.v1.captchas.store'), ['phone' => '13160675344']);
        $data = $response->json();
        $captcha_key = $data['data']['captcha_key'];
        $response = $this->post(route('api.v1.users.store'), [
            'name' => 'king',
            'password' => '123434',
            'verification_key' => $captcha_key,
            'verification_code' => (Cache::get($captcha_key))['code'],
        ]);
        $response->assertStatus(200);
    }
}
