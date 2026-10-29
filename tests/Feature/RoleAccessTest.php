<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_login_from_admin_routes(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
    }

    public function test_admin_can_access_the_dashboard(): void
    {
        $this->actingAs(User::factory()->admin()->create())
            ->get('/dashboard')
            ->assertOk();
    }

    public function test_solicitante_is_denied_access_to_the_dashboard(): void
    {
        $this->actingAs(User::factory()->solicitante()->create())
            ->get('/dashboard')
            ->assertForbidden()
            ->assertSee('Acceso Denegado');
    }

    public function test_solicitante_can_access_the_requests_screen(): void
    {
        $this->actingAs(User::factory()->solicitante()->create())
            ->get('/solicitudes')
            ->assertOk();
    }

    public function test_admin_is_denied_access_to_the_requests_screen(): void
    {
        $this->actingAs(User::factory()->admin()->create())
            ->get('/solicitudes')
            ->assertForbidden()
            ->assertSee('Acceso Denegado');
    }

    public function test_role_attribute_is_cast_and_normalised(): void
    {
        $user = User::factory()->admin()->create();

        $this->assertSame(UserRole::Admin, $user->role);
        $this->assertTrue($user->isAdmin());
        $this->assertFalse($user->isSolicitante());

        $user->role = ' SOLICITANTE ';
        $user->save();

        $this->assertSame(UserRole::Solicitante, $user->fresh()->role);
        $this->assertSame('solicitante', $user->getRawOriginal('role'));
    }
}
