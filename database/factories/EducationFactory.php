<?php

namespace Database\Factories;

use App\Enums\EducationLevel;
use App\Models\Education;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Education>
 */
class EducationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startYear = fake()->numberBetween(2012, 2021);

        return [
            'user_id' => User::factory()->jobSeeker(),
            'institution' => 'Universitas '.fake()->city(),
            'level' => EducationLevel::S1,
            'major' => fake()->randomElement(['Sistem Informasi', 'Teknik Informatika', 'Manajemen', 'Akuntansi', 'Desain Komunikasi Visual']),
            'start_year' => $startYear,
            'end_year' => $startYear + 4,
        ];
    }
}
