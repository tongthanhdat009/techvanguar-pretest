<?php

namespace Tests\Feature;

use App\Models\Deck;
use App\Models\DeckReview;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPortalUiTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_users_page_renders_custom_admin_ui_shell(): void
    {
        $admin = User::factory()->admin()->create();
        $managedUser = User::factory()->create();

        $response = $this->actingAs($admin)
            ->withSession(['status' => 'User deleted.'])
            ->get(route('admin.users'));

        $response->assertOk();
        $response->assertSee('data-admin-toast-stack', false);
        $response->assertSee('User deleted.');
        $response->assertSee('admin-sidebar-open');
        $response->assertSee('data-admin-confirm', false);
        // Confirm modal is used (not browser confirm())
        $response->assertSee('data-confirm-message', false);
        $response->assertDontSee("confirm('Are you sure you want to delete this user?')", false);
        $response->assertSee($managedUser->email);
    }

    public function test_admin_decks_and_reviews_use_custom_confirm_actions(): void
    {
        $admin = User::factory()->admin()->create();
        $client = User::factory()->create();
        $deck = Deck::factory()->create();
        DeckReview::create([
            'deck_id' => $deck->id,
            'user_id' => $client->id,
            'rating' => 5,
            'comment' => 'Helpful deck.',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.decks'))
            ->assertOk()
            ->assertSee('data-admin-confirm', false)
            ->assertSee('data-confirm-accept', false);

        $this->actingAs($admin)
            ->get(route('admin.reviews'))
            ->assertOk()
            ->assertSee('data-admin-confirm', false)
            ->assertSee('data-confirm-accept', false);
    }
}
