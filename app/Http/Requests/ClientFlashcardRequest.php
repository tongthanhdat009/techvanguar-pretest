<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientFlashcardRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'front_content' => ['required', 'string'],
            'back_content' => ['required', 'string'],
            'image_url' => ['nullable', 'url'],
            'audio_url' => ['nullable', 'url'],
            'hint' => ['nullable', 'string', 'max:255'],
        ];
    }
}