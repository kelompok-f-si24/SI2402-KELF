<?php

namespace Database\Factories;

use App\Enums\ApplicationStatus;
use App\Models\Application;
use App\Models\JobListing;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Application>
 */
class ApplicationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'job_listing_id' => JobListing::factory(),
            'user_id' => User::factory()->jobSeeker(),
            'cover_letter' => fake()->paragraph(),
            'status' => ApplicationStatus::Pending,
        ];
    }
}
