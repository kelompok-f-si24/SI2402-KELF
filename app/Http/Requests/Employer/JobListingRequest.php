<?php

namespace App\Http\Requests\Employer;

use App\Enums\EmploymentType;
use App\Enums\JobCategory;
use App\Enums\WorkSystem;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class JobListingRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', Rule::enum(JobCategory::class)],
            'location' => ['required', 'string', 'max:100'],
            'employment_type' => ['required', Rule::enum(EmploymentType::class)],
            'work_system' => ['required', Rule::enum(WorkSystem::class)],
            'salary_min' => ['nullable', 'integer', 'min:0', 'max:1000000000'],
            'salary_max' => ['nullable', 'integer', 'min:0', 'max:1000000000', 'gte:salary_min'],
            'description' => ['required', 'string', 'min:30', 'max:5000'],
            'responsibilities' => ['required', 'string', 'max:5000'],
            'qualifications' => ['required', 'string', 'max:5000'],
            'deadline' => ['required', 'date', 'after_or_equal:today'],
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
            'deadline.after_or_equal' => 'Batas akhir lamaran tidak boleh tanggal yang sudah lewat.',
        ];
    }
}
