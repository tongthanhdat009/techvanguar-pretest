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

    public function test_the_home_page_loads_with_flashcard_branding(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Flashcard Learning Hub');
        $response->assertSee('JWT API overview');
    }

    public function test_a_client_can_view_their_portal(): void
    {
        $client = User::factory()->create();
        $deck = Deck::factory()->create(['title' => 'Biology Basics']);
        Flashcard::factory()->create(['deck_id' => $deck->id, 'front_content' => 'What is DNA?']);

        $response = $this->actingAs($client)->get('/client');

        $response->assertOk();
        $response->assertSee('Biology Basics');
        $response->assertSee('What is DNA?');
    }
}
