<?php

namespace Tests\Feature;

use App\Models\Deck;
use App\Models\Flashcard;
use App\Models\StudyProgress;
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

    public function test_copying_same_public_deck_reuses_existing_copy(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        $deck = Deck::factory()->create([
            'visibility' => Deck::VISIBILITY_PUBLIC,
            'is_active' => true,
        ]);

        Flashcard::factory()->count(2)->create([
            'deck_id' => $deck->id,
        ]);

        $this->actingAs($user)
            ->post(route('client.decks.copy', $deck));

        $firstCopy = Deck::query()
            ->where('user_id', $user->id)
            ->where('source_deck_id', $deck->id)
            ->first();

        $this->assertNotNull($firstCopy);

        $this->actingAs($user)
            ->post(route('client.decks.copy', $deck), ['redirect_to' => 'study'])
            ->assertRedirect(route('client.decks.study', $firstCopy));

        $this->assertSame(1, Deck::query()
            ->where('user_id', $user->id)
            ->where('source_deck_id', $deck->id)
            ->count());
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

    public function test_deck_study_view_includes_all_cards_in_the_current_deck_session(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        $deck = Deck::factory()->privateOwned($user)->create();

        Flashcard::factory()->count(13)->create([
            'deck_id' => $deck->id,
        ]);

        $this->actingAs($user)
            ->get(route('client.decks.study', $deck))
            ->assertOk()
            ->assertSee('data-total-cards="13"', false);
    }

    public function test_client_can_update_owned_deck_without_resubmitting_cards_or_is_active(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        $deck = Deck::factory()->privateOwned($user)->create([
            'title' => 'Original Title',
            'description' => 'Original description',
            'visibility' => Deck::VISIBILITY_PRIVATE,
            'category' => 'Science',
            'tags' => ['alpha'],
            'is_active' => true,
        ]);

        $this->actingAs($user)
            ->put(route('client.decks.update', $deck), [
                'title' => 'Updated Deck Title',
                'description' => 'Updated description',
                'visibility' => Deck::VISIBILITY_PUBLIC,
                'category' => 'Technology',
                'tags' => 'beta, gamma',
            ])
            ->assertRedirect(route('client.decks.show', $deck))
            ->assertSessionHasNoErrors();

        $deck->refresh();

        $this->assertSame('Updated Deck Title', $deck->title);
        $this->assertSame('Updated description', $deck->description);
        $this->assertSame(Deck::VISIBILITY_PUBLIC, $deck->visibility);
        $this->assertSame('Technology', $deck->category);
        $this->assertSame(['beta', 'gamma'], $deck->tags);
        $this->assertTrue($deck->is_active);
    }

    public function test_client_can_save_study_progress_via_ajax_for_a_deck_session(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        $deck = Deck::factory()->privateOwned($user)->create();
        $flashcard = Flashcard::factory()->create([
            'deck_id' => $deck->id,
        ]);

        $this->actingAs($user)
            ->postJson(route('client.study.progress'), [
                'flashcard_id' => $flashcard->id,
                'deck_id' => $deck->id,
                'status' => StudyProgress::STATUS_LEARNING,
                'result' => 'good',
                'study_mode' => 'flip',
                'card_index' => 0,
            ])
            ->assertOk()
            ->assertJson([
                'success' => true,
                'flashcard_id' => $flashcard->id,
                'deck_id' => $deck->id,
            ]);

        $this->assertDatabaseHas('study_progress', [
            'user_id' => $user->id,
            'flashcard_id' => $flashcard->id,
            'status' => StudyProgress::STATUS_LEARNING,
            'review_count' => 1,
        ]);
    }
}
