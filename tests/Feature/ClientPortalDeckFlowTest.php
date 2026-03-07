<?php

namespace Tests\Feature;

use App\Models\Deck;
use App\Models\Flashcard;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientPortalDeckFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_client_can_copy_public_deck_with_its_flashcards(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        $deck = Deck::factory()->create([
            'title' => 'Shared Starter Deck',
            'visibility' => Deck::VISIBILITY_PUBLIC,
            'is_active' => true,
        ]);

        Flashcard::factory()->count(2)->create([
            'deck_id' => $deck->id,
        ]);

        $this->actingAs($user)
            ->post(route('client.decks.copy', $deck))
            ->assertRedirect();

        $copy = Deck::query()
            ->where('user_id', $user->id)
            ->where('source_deck_id', $deck->id)
            ->first();

        $this->assertNotNull($copy);
        $this->assertSame(Deck::VISIBILITY_PRIVATE, $copy->visibility);
        $this->assertSame(2, $copy->flashcards()->count());
    }

    public function test_client_cannot_copy_private_deck(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        $deck = Deck::factory()->privateOwned()->create();

        $this->actingAs($user)
            ->post(route('client.decks.copy', $deck))
            ->assertNotFound();
    }
}