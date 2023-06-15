<?php

namespace Tests;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Passport\ClientRepository;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use DatabaseMigrations;

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
}
