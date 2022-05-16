<?php

namespace Tests;

use App\Models\User;
use Database\Seeders\CountrySeeder;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(CountrySeeder::class);
    }

    public function initUser(bool $admin = true)
    {
        $this->actingAs($user = User::factory(['is_admin' => $admin])->create());

        return $user;
    }
}
