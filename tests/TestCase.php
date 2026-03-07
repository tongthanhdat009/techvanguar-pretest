<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function apiTokenFor(User $user): string
    {
        return auth('api')->login($user);
    }
}
