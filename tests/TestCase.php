<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Passport\ClientRepository;
use Laravel\Passport\Passport;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use DatabaseMigrations;

    // 创建密码方式的客户端
    public function createPasswordClient(): \Laravel\Passport\Client
    {
        $clientRepository = new ClientRepository();
        return $clientRepository->create(
            null,
            'Test Client',
            url('/redirect'),
            null,
            false,
            true
        );
    }

    // 登录用户
    protected function signIn($user = null)
    {
        $user = $user ?: User::factory()->create();
        Passport::actingAs($user);
        $this->actingAs($user);
        return $this;
    }
}
