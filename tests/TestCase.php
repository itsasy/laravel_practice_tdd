<?php

namespace Tests;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use App\Models\User;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, DatabaseMigrations;


    public function acting_as_user()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);
    }
}
