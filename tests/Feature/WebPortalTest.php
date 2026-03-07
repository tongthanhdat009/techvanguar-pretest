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
            ->assertRedirect(route('client.portal'))
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
        $deck = Deck::factory()->create(['title' => 'Biology Basics']);
        Flashcard::factory()->create(['deck_id' => $deck->id, 'front_content' => 'What is DNA?']);

        $response = $this->actingAs($client, 'client')->get('/client');

        $response->assertOk();
        $response->assertSee('Biology Basics');
        $response->assertSee('Community library');
    }

    public function test_public_and_client_navigation_render_without_inline_alpine_directives(): void
    {
        /** @var User $client */
        $client = User::factory()->create();

        $this->get('/')
            ->assertOk()
            ->assertSee('data-disclosure-toggle', false)
            ->assertSee('data-disclosure-panel', false)
            ->assertDontSee('x-data', false)
            ->assertDontSee('x-show', false);

        $this->actingAs($client, 'client')
            ->get(route('client.dashboard'))
            ->assertOk()
            ->assertSee('data-disclosure-toggle', false)
            ->assertSee('data-disclosure-panel', false)
            ->assertDontSee('x-data', false)
            ->assertDontSee('x-show', false);
    }

    public function test_client_logout_accepts_get_requests_and_redirects_to_login(): void
    {
        /** @var User $client */
        $client = User::factory()->create();

        $response = $this->actingAs($client, 'client')->get('/logout');

        $response->assertRedirect(route('client.login'));
        $this->assertGuest('client');
    }
}
