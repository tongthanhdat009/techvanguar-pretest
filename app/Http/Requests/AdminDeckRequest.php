<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\NormalizesTags;
use App\Models\Deck;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminDeckRequest extends FormRequest
{
    use NormalizesTags;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $deck = $this->route('deck'); // For update, may be null
        $userId = $this->input('user_id') ?? auth()->id();

        return [
            'user_id' => ['nullable', 'exists:users,id'],
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('decks', 'title')
                    ->where('user_id', $userId)
                    ->ignore($deck?->id),
            ],
            'description' => ['nullable', 'string'],
            'visibility' => ['required', Rule::in([Deck::VISIBILITY_PRIVATE, Deck::VISIBILITY_PUBLIC])],
            'category' => ['nullable', 'string', 'max:255'],
            'tags' => ['nullable', 'string'],
            'source_deck_id' => ['nullable', 'exists:decks,id'],
            'is_active' => ['required', 'boolean'],
        ];
    }

    public function validatedPayload(): array
    {
        $validated = $this->validated();
        $validated['tags'] = $this->normalizeTags($validated['tags'] ?? null);

        return $validated;
    }
}