<?php

namespace Tests;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function apiTokenFor(User $user): string
    {
        return auth('api')->login($user);
    }

    public function actingAs(Authenticatable $user, $guard = null): static
    {
        if ($guard === null && $user instanceof User) {
            $guard = match ($user->role) {
                User::ROLE_ADMIN => 'admin',
                User::ROLE_CLIENT => 'client',
                default => null,
            };
        }

        return parent::actingAs($user, $guard);
    }
}
