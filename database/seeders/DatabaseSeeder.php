<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            ['name' => 'Test User', 'password' => Hash::make('password')],
        );

        // Akun demo sesuai yang ditampilkan di halaman login (password: password).
        User::firstOrCreate(
            ['email' => 'admin@lokerin.test'],
            ['name' => 'Admin Lokerin', 'role' => UserRole::Admin, 'password' => Hash::make('password')],
        );

        User::firstOrCreate(
            ['email' => 'employer@lokerin.test'],
            ['name' => 'Employer Demo', 'role' => UserRole::Employer, 'password' => Hash::make('password')],
        );

        User::firstOrCreate(
            ['email' => 'seeker@lokerin.test'],
            ['name' => 'Job Seeker Demo', 'role' => UserRole::JobSeeker, 'password' => Hash::make('password')],
        );
    }
}
