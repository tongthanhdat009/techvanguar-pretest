<?php

namespace Tests\Feature;

use App\Models\Deck;
use App\Models\Flashcard;
use App\Models\StudyProgress;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_clients_only_receive_active_decks(): void
    {
        $user = User::factory()->create();
        $token = $this->apiTokenFor($user);

        Deck::factory()->create(['title' => 'Visible Deck', 'is_active' => true]);
        Deck::factory()->inactive()->create(['title' => 'Hidden Deck']);
        Deck::factory()->privateOwned()->create(['title' => 'Private Deck']);
        Deck::factory()->privateOwned($user)->create(['title' => 'My Private Deck']);

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/client/decks')
            ->assertOk()
            ->assertJsonFragment(['title' => 'Visible Deck'])
            ->assertJsonFragment(['title' => 'My Private Deck'])
            ->assertJsonMissing(['title' => 'Private Deck'])
            ->assertJsonMissing(['title' => 'Hidden Deck']);
    }

    public function test_clients_can_update_flashcard_study_progress(): void
    {
        $user = User::factory()->create();
        $token = $this->apiTokenFor($user);
        $flashcard = Flashcard::factory()->create();

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->putJson("/api/client/flashcards/{$flashcard->id}/progress", [
                'status' => StudyProgress::STATUS_MASTERED,
                'result' => 'easy',
            ])
            ->assertOk()
            ->assertJsonPath('status', StudyProgress::STATUS_MASTERED)
            ->assertJsonPath('correct_streak', 1);

        $this->assertDatabaseHas('study_progress', [
            'user_id' => $user->id,
            'flashcard_id' => $flashcard->id,
            'status' => StudyProgress::STATUS_MASTERED,
        ]);
    }

    public function test_private_deck_is_visible_to_owner_but_not_other_clients(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $ownerToken = $this->apiTokenFor($owner);
        $deck = Deck::factory()->privateOwned($owner)->create([
            'title' => 'Owner Private Deck',
        ]);

        Flashcard::factory()->create([
            'deck_id' => $deck->id,
            'front_content' => 'Front side',
            'back_content' => 'Back side',
        ]);

        $this->withHeader('Authorization', 'Bearer '.$ownerToken)
            ->getJson("/api/client/decks/{$deck->id}")
            ->assertOk()
            ->assertJsonPath('title', 'Owner Private Deck');

        $otherToken = $this->apiTokenFor($otherUser);

        $this->withHeader('Authorization', 'Bearer '.$otherToken)
            ->getJson("/api/client/decks/{$deck->id}")
            ->assertNotFound();
    }
}
