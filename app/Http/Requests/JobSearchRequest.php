<?php

namespace App\Http\Requests;

use App\Enums\EmploymentType;
use App\Enums\JobCategory;
use App\Enums\WorkSystem;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class JobSearchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
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
        return [
            'q' => ['nullable', 'string', 'max:100'],
            'location' => ['nullable', 'string', 'max:100'],
            'category' => ['nullable', Rule::enum(JobCategory::class)],
            'employment_type' => ['nullable', Rule::enum(EmploymentType::class)],
            'work_system' => ['nullable', Rule::enum(WorkSystem::class)],
            'salary_min' => ['nullable', 'integer', 'min:0'],
            'salary_max' => ['nullable', 'integer', 'min:0', 'gte:salary_min'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'salary_max.gte' => 'Gaji maksimum harus lebih besar atau sama dengan gaji minimum.',
            'salary_min.integer' => 'Gaji minimum harus berupa angka.',
            'salary_max.integer' => 'Gaji maksimum harus berupa angka.',
        ];
    }
}
