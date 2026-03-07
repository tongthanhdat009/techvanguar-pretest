<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\NormalizesTags;
use App\Models\Deck;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminDeckImportRequest extends FormRequest
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
            'category' => ['nullable', 'string', 'max:255'],
            'tags' => ['nullable', 'string'],
            'visibility' => ['required', Rule::in([Deck::VISIBILITY_PRIVATE, Deck::VISIBILITY_PUBLIC])],
            'is_active' => ['required', 'boolean'],
            'user_id' => ['nullable', 'exists:users,id'],
            'csv_file' => ['required', 'file', 'mimes:csv,txt'],
        ];
    }

    public function validatedPayload(): array
    {
        $validated = $this->validated();
        $validated['tags'] = $this->normalizeTags($validated['tags'] ?? null);

        return $validated;
    }
}