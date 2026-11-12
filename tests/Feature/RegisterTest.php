<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_page_can_be_rendered(): void
    {
        $this->get(route('register'))
            ->assertOk()
            ->assertSee('Buat Akun Baru')
            ->assertSee('Job Seeker')
            ->assertSee('Employer');
    }

    public function test_guest_can_register_as_job_seeker(): void
    {
        $response = $this->post(route('register.store'), [
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
            'role' => 'job_seeker',
        ]);

        $response->assertRedirect(route('seeker.dashboard'))->assertSessionHas('success');
        $this->assertAuthenticated();

        $user = User::where('email', 'budi@example.com')->firstOrFail();
        $this->assertSame('job_seeker', $user->role->value);
        $this->assertTrue(Hash::check('rahasia123', $user->password));
    }

    public function test_guest_can_register_as_employer(): void
    {
        $response = $this->post(route('register.store'), [
            'name' => 'PT Maju Jaya',
            'email' => 'hrd@majujaya.test',
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
            'role' => 'employer',
        ]);

        $response->assertRedirect(route('employer.dashboard'))->assertSessionHas('success');
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', ['email' => 'hrd@majujaya.test', 'role' => 'employer']);
    }

    public function test_admin_role_cannot_be_chosen_on_registration(): void
    {
        $response = $this->post(route('register.store'), [
            'name' => 'Penyusup',
            'email' => 'penyusup@example.com',
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
            'role' => 'admin',
        ]);

        $response->assertSessionHasErrors('role');
        $this->assertGuest();
        $this->assertDatabaseMissing('users', ['email' => 'penyusup@example.com']);
    }

    public function test_registration_validation_requires_unique_email_and_confirmed_password(): void
    {
        User::factory()->create(['email' => 'taken@example.com']);

        $this->post(route('register.store'), [
            'name' => 'Duplikat',
            'email' => 'taken@example.com',
            'password' => 'rahasia123',
            'password_confirmation' => 'salah',
            'role' => 'job_seeker',
        ])->assertSessionHasErrors(['email', 'password']);

        $this->post(route('register.store'), [
            'name' => 'Tanpa Password',
            'email' => 'baru@example.com',
            'role' => 'job_seeker',
        ])->assertSessionHasErrors(['password']);

        $this->assertGuest();
    }
}
