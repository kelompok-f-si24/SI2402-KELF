<?php

namespace App\Http\Requests\Seeker;

use App\Enums\EducationLevel;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EducationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $maxYear = now()->year + 7;

        return [
            'institution' => ['required', 'string', 'max:255'],
            'level' => ['required', Rule::enum(EducationLevel::class)],
            'major' => ['nullable', 'string', 'max:255'],
            'start_year' => ['required', 'integer', 'min:1950', 'max:'.now()->year],
            'end_year' => ['nullable', 'integer', 'gte:start_year', 'max:'.$maxYear],
        ];
    }
}
