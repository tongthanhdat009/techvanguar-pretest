<?php

namespace App\Http\Requests;

use App\Models\StudyProgress;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RecordStudyProgressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'flashcard_id' => ['required', 'integer', 'exists:flashcards,id'],
            'deck_id' => ['nullable', 'integer', 'exists:decks,id'],
            'status' => ['required', Rule::in(StudyProgress::statuses())],
            'result' => ['nullable', Rule::in(['again', 'hard', 'good', 'easy'])],
            'study_mode' => ['nullable', Rule::in(['flip', 'multiple-choice', 'typed'])],
            'card_index' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
