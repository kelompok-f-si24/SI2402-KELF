<?php

namespace Database\Factories;

use App\Enums\EmploymentType;
use App\Enums\JobCategory;
use App\Enums\JobStatus;
use App\Enums\WorkSystem;
use App\Models\Company;
use App\Models\JobListing;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<JobListing>
 */
class JobListingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $salaryMin = fake()->numberBetween(4, 15) * 1_000_000;

        return [
            'company_id' => Company::factory(),
            'title' => fake()->randomElement([
                'Backend Developer', 'Frontend Developer', 'UI/UX Designer', 'Data Analyst',
                'Digital Marketing Specialist', 'Accounting Staff', 'HR Generalist', 'Sales Executive',
                'Product Manager', 'Customer Service', 'Mobile Developer', 'Content Writer',
            ]),
            'category' => fake()->randomElement(JobCategory::cases()),
            'location' => fake()->randomElement(['Jakarta', 'Bandung', 'Surabaya', 'Yogyakarta', 'Bali', 'Medan']),
            'employment_type' => fake()->randomElement(EmploymentType::cases()),
            'work_system' => fake()->randomElement(WorkSystem::cases()),
            'salary_min' => $salaryMin,
            'salary_max' => $salaryMin + fake()->numberBetween(1, 6) * 1_000_000,
            'description' => fake()->paragraphs(2, true),
            'responsibilities' => implode("\n", fake()->sentences(4)),
            'qualifications' => implode("\n", fake()->sentences(4)),
            'deadline' => fake()->dateTimeBetween('+1 week', '+2 months'),
            'status' => JobStatus::Active,
            'published_at' => fake()->dateTimeBetween('-2 months', 'now'),
        ];
    }

    public function closed(): static
    {
        return $this->state(fn (array $attributes) => ['status' => JobStatus::Closed]);
    }

    public function expired(): static
    {
        return $this->state(fn (array $attributes) => ['deadline' => now()->subDay()]);
    }
}
