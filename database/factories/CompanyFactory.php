<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->company();

        return [
            'user_id' => User::factory()->employer(),
            'name' => $name,
            'slug' => Str::slug($name).'-'.Str::lower(Str::random(4)),
            'industry' => fake()->randomElement(['Teknologi', 'Keuangan', 'Retail', 'Manufaktur', 'Pendidikan', 'Kesehatan', 'Media']),
            'address' => fake()->address(),
            'description' => fake()->paragraphs(2, true),
            'website' => fake()->url(),
            'email' => fake()->companyEmail(),
            'phone' => fake()->numerify('021#######'),
            'is_verified' => fake()->boolean(70),
        ];
    }
}
