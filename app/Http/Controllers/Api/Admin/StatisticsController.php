<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deck;
use App\Models\Flashcard;
use App\Models\StudyProgress;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class StatisticsController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $progressTotal = StudyProgress::count();
        $masteredCount = StudyProgress::where('status', StudyProgress::STATUS_MASTERED)->count();

        return response()->json([
            'users' => User::count(),
            'admins' => User::where('role', User::ROLE_ADMIN)->count(),
            'clients' => User::where('role', User::ROLE_CLIENT)->count(),
            'active_decks' => Deck::where('is_active', true)->count(),
            'total_decks' => Deck::count(),
            'flashcards' => Flashcard::count(),
            'study_progress' => $progressTotal,
            'progress_by_status' => StudyProgress::query()
                ->selectRaw('status, count(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status'),
            'mastered_percentage' => $progressTotal === 0 ? 0 : round(($masteredCount / $progressTotal) * 100, 2),
        ]);
    }
}
