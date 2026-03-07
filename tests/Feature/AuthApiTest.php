<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_client_can_register_and_receive_a_jwt_token(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => 'New Learner',
            'email' => 'learner@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response
            ->assertCreated()
            ->assertJsonPath('user.email', 'learner@example.com')
            ->assertJsonPath('user.role', User::ROLE_CLIENT)
            ->assertJsonStructure(['access_token', 'token_type', 'expires_in', 'user']);

        $this->assertDatabaseHas('users', [
            'email' => 'learner@example.com',
            'role' => User::ROLE_CLIENT,
        ]);
    }

    public function test_an_authenticated_user_can_fetch_their_profile(): void
    {
        $user = User::factory()->create();
        $token = $this->apiTokenFor($user);

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/auth/me')
            ->assertOk()
            ->assertJsonPath('email', $user->email);
    }
}
