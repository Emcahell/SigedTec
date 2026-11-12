<?php

namespace Tests\Feature;

use App\Enums\EquipmentStatus;
use App\Models\Category;
use App\Models\Equipment;
use App\Models\Request;
use App\Models\RequestItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_registration_is_disabled(): void
    {
        $this->get('/register')->assertNotFound();

        $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertNotFound();

        $this->assertGuest();
    }

    public function test_login_screen_does_not_offer_registration(): void
    {
        $this->get('/login')
            ->assertOk()
            ->assertDontSee('Regístrese aquí');
    }

    public function test_admin_can_register_a_requester_with_a_password(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get(route('users.create'))
            ->assertOk();

        $response = $this->actingAs($admin)->post(route('users.store'), [
            'name' => 'Jefa de Oficina',
            'email' => 'jefa.oficina@institucion.gob.pe',
            'password' => 'clave-segura-123',
            'password_confirmation' => 'clave-segura-123',
        ]);

        $response->assertRedirect(route('users.index'))->assertSessionHasNoErrors();

        $user = User::firstWhere('email', 'jefa.oficina@institucion.gob.pe');

        $this->assertNotNull($user);
        $this->assertSame('solicitante', $user->role?->value);
        $this->assertTrue($user->isSolicitante());
        $this->assertNotNull($user->email_verified_at);
        $this->assertTrue(
            Hash::check('clave-segura-123', $user->password),
        );
    }

    public function test_new_requester_can_log_in_with_the_assigned_password(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->post(route('users.store'), [
            'name' => 'Jefe de Tesorería',
            'email' => 'tesoreria@institucion.gob.pe',
            'password' => 'clave-segura-123',
            'password_confirmation' => 'clave-segura-123',
        ]);

        auth()->logout();

        $this->post('/login', [
            'email' => 'tesoreria@institucion.gob.pe',
            'password' => 'clave-segura-123',
        ])->assertRedirect('/requests/mine');

        $this->assertAuthenticated();
    }

    public function test_user_creation_validates_email_uniqueness_and_password(): void
    {
        $admin = User::factory()->admin()->create();

        User::factory()->solicitante()->create(['email' => 'duplicado@institucion.gob.pe']);

        $this->actingAs($admin)
            ->post(route('users.store'), [
                'name' => 'Otro Usuario',
                'email' => 'duplicado@institucion.gob.pe',
                'password' => 'clave-segura-123',
                'password_confirmation' => 'clave-segura-123',
            ])
            ->assertSessionHasErrors('email');

        $this->actingAs($admin)
            ->post(route('users.store'), [
                'name' => 'Otro Usuario',
                'email' => 'nuevo@institucion.gob.pe',
                'password' => 'clave-segura-123',
                'password_confirmation' => 'distinta',
            ])
            ->assertSessionHasErrors('password');
    }

    public function test_solicitante_cannot_manage_users(): void
    {
        $solicitante = User::factory()->solicitante()->create();

        $this->actingAs($solicitante)
            ->get(route('users.index'))
            ->assertForbidden()
            ->assertSee('Acceso Denegado');
    }

    public function test_admin_can_edit_a_requester_without_changing_the_password(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->solicitante()->create([
            'name' => 'Nombre anterior',
            'email' => 'anterior@institucion.gob.pe',
        ]);
        $originalPassword = $user->password;

        $this->actingAs($admin)
            ->get(route('users.edit', $user))
            ->assertOk();

        $this->actingAs($admin)
            ->patch(route('users.update', $user), [
                'name' => 'Nombre actualizado',
                'email' => 'actualizado@institucion.gob.pe',
                'password' => '',
                'password_confirmation' => '',
            ])
            ->assertRedirect(route('users.index'))
            ->assertSessionHasNoErrors();

        $user->refresh();

        $this->assertSame('Nombre actualizado', $user->name);
        $this->assertSame('actualizado@institucion.gob.pe', $user->email);
        $this->assertSame($originalPassword, $user->password);
        $this->assertTrue($user->isSolicitante());
    }

    public function test_admin_can_change_a_requester_password(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->solicitante()->create();

        $this->actingAs($admin)
            ->patch(route('users.update', $user), [
                'name' => $user->name,
                'email' => $user->email,
                'password' => 'nueva-clave-123',
                'password_confirmation' => 'nueva-clave-123',
            ])
            ->assertSessionHasNoErrors();

        $this->assertTrue(Hash::check('nueva-clave-123', $user->refresh()->password));
    }

    public function test_admin_account_cannot_be_edited_or_deleted(): void
    {
        $admin = User::factory()->admin()->create();
        $otherAdmin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get(route('users.edit', $otherAdmin))
            ->assertForbidden();

        $this->actingAs($admin)
            ->delete(route('users.destroy', $otherAdmin))
            ->assertRedirect(route('users.index'))
            ->assertSessionHas('error');

        $this->assertNotNull($otherAdmin->fresh());
    }

    public function test_admin_can_delete_a_requester_and_their_equipment_is_released(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->solicitante()->create();
        $category = Category::factory()->create();
        $equipment = Equipment::factory()->asignado()->create(['category_id' => $category->id]);

        $request = Request::create([
            'user_id' => $user->id,
            'office' => 'Oficina de Tesorería',
            'category_id' => $category->id,
            'quantity' => 1,
            'status' => 'aprobada',
        ]);

        RequestItem::create([
            'request_id' => $request->id,
            'equipment_id' => $equipment->id,
            'assigned_at' => now(),
        ]);

        $this->actingAs($admin)
            ->delete(route('users.destroy', $user))
            ->assertRedirect(route('users.index'))
            ->assertSessionHas('success');

        $this->assertNull($user->fresh());
        $this->assertSame(EquipmentStatus::Disponible, $equipment->fresh()->estado);
    }

    public function test_admin_sees_edit_and_delete_actions_for_requesters_only(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->solicitante()->create();

        $this->actingAs($admin)
            ->get(route('users.index'))
            ->assertOk()
            ->assertSee(route('users.edit', $user), false)
            ->assertSee('Cuenta protegida');
    }
}
