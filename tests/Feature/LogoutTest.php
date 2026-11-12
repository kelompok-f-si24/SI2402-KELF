<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_logged_in_user_can_logout_safely(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('logout'));

        $response->assertRedirect(route('home'))->assertSessionHas('success');
        $this->assertGuest();
    }

    public function test_session_is_invalidated_after_logout(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('logout'))
            ->assertRedirect(route('home'));

        // Sesi lama sudah tidak berlaku: halaman yang butuh login diarahkan ke halaman login.
        $this->get(route('seeker.dashboard'))->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_guest_cannot_logout(): void
    {
        $this->post(route('logout'))->assertRedirect(route('login'));
        $this->assertGuest();
    }
}
