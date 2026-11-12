<?php

namespace Database\Factories;

use App\Enums\SkillLevel;
use App\Enums\SkillType;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SkillFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->jobSeeker(),
            'name' => fake()->randomElement(['Laravel', 'Komunikasi', 'Figma', 'Kepemimpinan', 'SQL', 'Kerja Tim', 'Excel']),
            'type' => fake()->randomElement(SkillType::cases()),
            'level' => fake()->randomElement(SkillLevel::cases()),
        ];
    }
}
