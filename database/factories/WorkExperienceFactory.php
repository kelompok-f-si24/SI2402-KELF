<?php

namespace Database\Factories;

use App\Enums\EmploymentType;
use App\Models\User;
use App\Models\WorkExperience;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkExperienceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('-5 years', '-1 year');

        return [
            'user_id' => User::factory()->jobSeeker(),
            'company_name' => fake()->company(),
            'position' => fake()->jobTitle(),
            'employment_type' => fake()->randomElement(EmploymentType::cases()),
            'start_date' => $startDate,
            'end_date' => fake()->dateTimeBetween($startDate, 'now'),
            'description' => fake()->sentences(3, true),
        ];
    }
}
