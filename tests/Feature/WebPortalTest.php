<?php

namespace Tests\Feature;

use App\Models\Deck;
use App\Models\Flashcard;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WebPortalTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_home_page_loads_with_public_product_copy_only(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Flashcard Learning Hub');
        $response->assertSee('Evidence-Based Learning Platform');
        $response->assertDontSee('JWT API overview');
        $response->assertDontSee('admin@example.com');
    }

    public function test_client_and_admin_login_pages_are_separate(): void
    {
        $this->get('/login/client')
            ->assertOk()
            ->assertSee('Login as client')
            ->assertDontSee('Login as admin');

        $this->get('/login/admin')
            ->assertOk()
            ->assertSee('Login as admin')
            ->assertDontSee('Login as client');
    }

    public function test_client_session_does_not_block_admin_login_flow_and_uses_separate_cookie(): void
    {
        $client = User::factory()->create([
            'email' => 'client@example.com',
            'role' => User::ROLE_CLIENT,
            'password' => 'password',
        ]);
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'role' => User::ROLE_ADMIN,
            'password' => 'password',
        ]);

        $clientLogin = $this->post('/login/client', [
            'email' => $client->email,
            'password' => 'password',
        ]);

        $clientLogin
            ->assertRedirect(route('client.dashboard'))
            ->assertCookie('flashcard_learning_hub_client_session');

        $clientCookie = $clientLogin->getCookie('flashcard_learning_hub_client_session');

        $this->withCookie($clientCookie->getName(), $clientCookie->getValue())
            ->get('/login/admin')
            ->assertOk()
            ->assertSee('Login as admin');

        $this->withCookie($clientCookie->getName(), $clientCookie->getValue())
            ->post('/login/admin', [
                'email' => $admin->email,
                'password' => 'password',
            ])
            ->assertRedirect(route('admin.overview'));
    }

    public function test_a_client_can_view_their_portal(): void
    {
        /** @var User $client */
        $client = User::factory()->create();
        $deck = Deck::factory()->privateOwned($client)->create(['title' => 'Biology Basics']);
        Flashcard::factory()->create(['deck_id' => $deck->id, 'front_content' => 'What is DNA?']);

        $response = $this->actingAs($client, 'client')->get('/client');

        $response->assertOk();
        $response->assertSee('Biology Basics');
        $response->assertSee('Thư viện của bạn');
        $response->assertSee('Gợi ý từ cộng đồng');
    }

    public function test_client_logout_redirects_to_client_login(): void
    {
        /** @var User $client */
        $client = User::factory()->create();

        $response = $this->actingAs($client, 'client')->post('/client/logout');

        $response->assertRedirect(route('client.login'));
        $this->assertGuest('client');
    }

    public function test_admin_portal_renders_admin_logout_route(): void
    {
        /** @var User $admin */
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);

        $response = $this->actingAs($admin, 'admin')->get('/admin');

        $response->assertOk();
        $response->assertSee(route('admin.logout'), false);
    }

    public function test_admin_logout_redirects_to_admin_login(): void
    {
        /** @var User $admin */
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);

        $response = $this->actingAs($admin, 'admin')->post('/admin/logout');

        $response->assertRedirect(route('admin.login'));
        $this->assertGuest('admin');
    }

    public function test_new_client_dashboard_does_not_show_due_cards_from_public_decks(): void
    {
        /** @var User $client */
        $client = User::factory()->create([
            'role' => User::ROLE_CLIENT,
        ]);

        $publicDeck = Deck::factory()->create([
            'visibility' => Deck::VISIBILITY_PUBLIC,
            'is_active' => true,
        ]);

        Flashcard::factory()->count(2)->create([
            'deck_id' => $publicDeck->id,
        ]);

        $this->actingAs($client, 'client')
            ->get('/client')
            ->assertOk()
            ->assertSee('Hàng chờ hôm nay đã sạch')
            ->assertSee('Không có thẻ đến hạn. Bạn có thể tạo deck mới hoặc chuyển qua thư viện cộng đồng để lấy thêm nội dung.');
    }
}
