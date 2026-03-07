<?php

namespace App\Support;

use App\Models\Deck;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class DeckAccess
{
    public function accessibleQuery(User $user): Builder
    {
        return Deck::query()->where(function (Builder $query) use ($user) {
            $query->where('user_id', $user->id)
                ->orWhere(function (Builder $publicQuery) {
                    $publicQuery->where('visibility', Deck::VISIBILITY_PUBLIC)
                        ->where('is_active', true);
                });
        });
    }

    public function canAccess(User $user, Deck $deck): bool
    {
        return (int) $deck->user_id === (int) $user->id || $this->isPubliclyAvailable($deck);
    }

    public function canManage(User $user, Deck $deck): bool
    {
        return (int) $deck->user_id === (int) $user->id;
    }

    public function canCloneOrReview(Deck $deck): bool
    {
        return $this->isPubliclyAvailable($deck);
    }

    private function isPubliclyAvailable(Deck $deck): bool
    {
        return $deck->visibility === Deck::VISIBILITY_PUBLIC && $deck->is_active;
    }
}