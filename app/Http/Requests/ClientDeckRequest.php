<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\NormalizesTags;
use App\Models\Deck;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClientDeckRequest extends FormRequest
{
    use NormalizesTags;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'visibility' => ['required', Rule::in([Deck::VISIBILITY_PRIVATE, Deck::VISIBILITY_PUBLIC])],
            'category' => ['nullable', 'string', 'max:255'],
            'tags' => ['nullable', 'string'],
            'is_active' => ['required', 'boolean'],
            'cards' => ['required', 'array', 'min:1', 'max:50'],
            'cards.*.front' => ['required', 'string'],
            'cards.*.back' => ['required', 'string'],
            'cards.*.image_url' => ['nullable', 'url'],
            'cards.*.audio_url' => ['nullable', 'url'],
            'cards.*.hint' => ['nullable', 'string'],
        ];
    }

    public function validatedPayload(): array
    {
        $validated = $this->validated();
        $validated['tags'] = $this->normalizeTags($validated['tags'] ?? null);

        return $validated;
    }

    /**
     * Get cards data from request
     */
    public function cards(): array
    {
        return $this->input('cards', []);
    }
}