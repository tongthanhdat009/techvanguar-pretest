<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_admins_can_create_decks_and_fetch_statistics(): void
    {
        $admin = User::factory()->admin()->create();
        $token = $this->apiTokenFor($admin);

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/admin/decks', [
                'title' => 'Algorithms',
                'description' => 'Sorting and searching fundamentals.',
                'is_active' => true,
            ])
            ->assertCreated()
            ->assertJsonPath('title', 'Algorithms');

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/admin/statistics')
            ->assertOk()
            ->assertJsonPath('total_decks', 1)
            ->assertJsonPath('admins', 1);
    }

    public function test_clients_cannot_access_admin_statistics(): void
    {
        $client = User::factory()->create();
        $token = $this->apiTokenFor($client);

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/admin/statistics')
            ->assertForbidden();
    }
}
