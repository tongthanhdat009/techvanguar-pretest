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

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/client/decks')
            ->assertOk()
            ->assertJsonFragment(['title' => 'Visible Deck'])
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
            ])
            ->assertOk()
            ->assertJsonPath('status', StudyProgress::STATUS_MASTERED);

        $this->assertDatabaseHas('study_progress', [
            'user_id' => $user->id,
            'flashcard_id' => $flashcard->id,
            'status' => StudyProgress::STATUS_MASTERED,
        ]);
    }
}
