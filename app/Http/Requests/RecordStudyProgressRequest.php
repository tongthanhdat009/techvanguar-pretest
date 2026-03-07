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
            'status' => ['required', Rule::in(StudyProgress::statuses())],
            'result' => ['nullable', Rule::in(['again', 'hard', 'good', 'easy'])],
        ];
    }
}