<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $this->assertSame('solicitante', User::firstWhere('email', 'test@example.com')?->role?->value);
        $response->assertRedirect('/requests/mine');
    }

    public function test_registration_honours_the_selected_role(): void
    {
        $response = $this->post('/register', [
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'admin',
        ]);

        $this->assertAuthenticated();
        $this->assertSame('admin', User::firstWhere('email', 'admin@example.com')?->role?->value);
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_registration_rejects_an_unknown_role(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'superadmin',
        ]);

        $response->assertSessionHasErrors('role');
        $this->assertGuest();
    }
}
