<?php

namespace Tests\Feature;

use App\Enums\EquipmentStatus;
use App\Enums\RequestStatus;
use App\Models\Category;
use App\Models\Equipment;
use App\Models\Request;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RequestFlowTest extends TestCase
{
    use RefreshDatabase;

    private function makeRequest(array $attributes = []): Request
    {
        $category = $attributes['category'] ?? Category::factory()->create();
        $user = $attributes['user'] ?? User::factory()->solicitante()->create();

        return Request::create([
            'user_id' => $user->id,
            'office' => $attributes['office'] ?? 'Oficina de Tesorería',
            'category_id' => $category->id,
            'quantity' => $attributes['quantity'] ?? 1,
            'status' => $attributes['status'] ?? RequestStatus::Pendiente->value,
        ]);
    }

    public function test_solicitante_can_create_a_request(): void
    {
        $user = User::factory()->solicitante()->create();
        $category = Category::factory()->create(['nombre' => 'Laptops']);

        $this->actingAs($user)
            ->get(route('requests.create'))
            ->assertOk()
            ->assertSee('Laptops');

        $response = $this->actingAs($user)->post(route('requests.store'), [
            'office' => 'Oficina de Tesorería',
            'category_id' => $category->id,
            'quantity' => 3,
        ]);

        $solicitud = Request::sole();

        $response->assertRedirect(route('requests.show', $solicitud));

        $this->assertSame($user->id, $solicitud->user_id);
        $this->assertSame($category->id, $solicitud->category_id);
        $this->assertSame(3, $solicitud->quantity);
        $this->assertSame(RequestStatus::Pendiente, $solicitud->status);
        $this->assertNull($solicitud->rejection_reason);
    }

    public function test_request_creation_validates_quantity_and_category(): void
    {
        $user = User::factory()->solicitante()->create();
        $category = Category::factory()->create();

        $this->actingAs($user)
            ->post(route('requests.store'), [
                'office' => 'Oficina de Tesorería',
                'category_id' => $category->id,
                'quantity' => 0,
            ])
            ->assertSessionHasErrors('quantity');

        $this->actingAs($user)
            ->post(route('requests.store'), [
                'office' => 'Oficina de Tesorería',
                'category_id' => 99999,
                'quantity' => 2,
            ])
            ->assertSessionHasErrors('category_id');

        $this->actingAs($user)
            ->post(route('requests.store'), [
                'category_id' => $category->id,
                'quantity' => 2,
            ])
            ->assertSessionHasErrors('office');

        $this->assertSame(0, Request::count());
    }

    public function test_requester_only_sees_their_own_requests(): void
    {
        $owner = User::factory()->solicitante()->create();
        $other = User::factory()->solicitante()->create();

        $this->makeRequest(['user' => $owner, 'office' => 'Oficina de Tesorería']);
        $this->makeRequest(['user' => $other, 'office' => 'Oficina de Logística']);

        $this->actingAs($owner)
            ->get(route('requests.mine'))
            ->assertOk()
            ->assertSee('Oficina de Tesorería')
            ->assertDontSee('Oficina de Logística');
    }

    public function test_requester_can_not_view_someone_elses_request(): void
    {
        $owner = User::factory()->solicitante()->create();
        $other = User::factory()->solicitante()->create();
        $solicitud = $this->makeRequest(['user' => $owner]);

        $this->actingAs($owner)
            ->get(route('requests.show', $solicitud))
            ->assertOk();

        $this->actingAs($other)
            ->get(route('requests.show', $solicitud))
            ->assertForbidden();
    }

    public function test_admin_lists_pending_requests_and_can_filter_by_status(): void
    {
        $admin = User::factory()->admin()->create();

        $pendiente = $this->makeRequest(['office' => 'Oficina de Tesorería']);
        $aprobada = $this->makeRequest(['office' => 'Oficina de Logística', 'status' => RequestStatus::Aprobada->value]);

        $this->actingAs($admin)
            ->get(route('requests.index'))
            ->assertOk()
            ->assertSee('Oficina de Tesorería')
            ->assertDontSee('Oficina de Logística');

        $this->actingAs($admin)
            ->get(route('requests.index', ['estado' => 'aprobada']))
            ->assertOk()
            ->assertSee('Oficina de Logística')
            ->assertDontSee('Oficina de Tesorería');

        $this->actingAs($admin)
            ->get(route('requests.index', ['estado' => '']))
            ->assertOk()
            ->assertSee($pendiente->office)
            ->assertSee($aprobada->office);
    }

    public function test_admin_can_approve_a_request_and_equipment_is_assigned_atomically(): void
    {
        $admin = User::factory()->admin()->create();
        $category = Category::factory()->create(['nombre' => 'Laptops']);
        $equipos = Equipment::factory()->count(2)->disponible()->create(['category_id' => $category->id]);
        $solicitud = $this->makeRequest(['category' => $category, 'quantity' => 2]);

        $response = $this->actingAs($admin)->post(route('requests.approve.store', $solicitud), [
            'equipment_ids' => $equipos->pluck('id')->all(),
        ]);

        $response->assertRedirect(route('requests.show', $solicitud))->assertSessionHasNoErrors();

        $solicitud->refresh();

        $this->assertSame(RequestStatus::Aprobada, $solicitud->status);
        $this->assertCount(2, $solicitud->items);

        foreach ($solicitud->items as $item) {
            $this->assertNotNull($item->assigned_at);
            $this->assertSame(EquipmentStatus::Asignado, $item->equipment->estado);
        }

        foreach ($equipos as $equipo) {
            $this->assertSame(EquipmentStatus::Asignado, $equipo->fresh()->estado);
        }
    }

    public function test_admin_can_render_the_management_views_for_a_pending_request(): void
    {
        $admin = User::factory()->admin()->create();
        $category = Category::factory()->create(['nombre' => 'Impresoras']);
        Equipment::factory()->disponible()->create(['category_id' => $category->id]);
        $solicitud = $this->makeRequest(['category' => $category, 'quantity' => 1]);

        $this->actingAs($admin)
            ->get(route('requests.show', $solicitud))
            ->assertOk()
            ->assertSee('Impresoras')
            ->assertSee('Aprobar solicitud');

        $this->actingAs($admin)
            ->get(route('requests.approve', $solicitud))
            ->assertOk()
            ->assertSee('Confirmar aprobación');

        $this->actingAs($admin)
            ->get(route('requests.reject', $solicitud))
            ->assertOk()
            ->assertSee('Motivo del rechazo');
    }

    public function test_approval_requires_the_exact_quantity_of_available_equipment(): void
    {
        $admin = User::factory()->admin()->create();
        $category = Category::factory()->create();
        $equipo = Equipment::factory()->disponible()->create(['category_id' => $category->id]);
        $solicitud = $this->makeRequest(['category' => $category, 'quantity' => 2]);

        $this->actingAs($admin)
            ->post(route('requests.approve.store', $solicitud), [
                'equipment_ids' => [$equipo->id],
            ])
            ->assertSessionHasErrors('equipment_ids');

        $this->assertSame(RequestStatus::Pendiente, $solicitud->fresh()->status);
        $this->assertSame(EquipmentStatus::Disponible, $equipo->fresh()->estado);
        $this->assertSame(0, $solicitud->items()->count());
    }

    public function test_approval_rejects_equipment_that_is_not_available_or_of_another_category(): void
    {
        $admin = User::factory()->admin()->create();
        $category = Category::factory()->create();
        $otherCategory = Category::factory()->create();

        $disponible = Equipment::factory()->disponible()->create(['category_id' => $category->id]);
        $mantenimiento = Equipment::factory()->mantenimiento()->create(['category_id' => $category->id]);
        $ajeno = Equipment::factory()->disponible()->create(['category_id' => $otherCategory->id]);

        $solicitud = $this->makeRequest(['category' => $category, 'quantity' => 2]);

        $this->actingAs($admin)
            ->post(route('requests.approve.store', $solicitud), [
                'equipment_ids' => [$disponible->id, $mantenimiento->id],
            ])
            ->assertSessionHasErrors('equipment_ids');

        $this->actingAs($admin)
            ->post(route('requests.approve.store', $solicitud), [
                'equipment_ids' => [$disponible->id, $ajeno->id],
            ])
            ->assertSessionHasErrors('equipment_ids');

        $this->assertSame(RequestStatus::Pendiente, $solicitud->fresh()->status);
        $this->assertSame(0, $solicitud->items()->count());
        $this->assertSame(EquipmentStatus::Disponible, $disponible->fresh()->estado);
    }

    public function test_a_processed_request_can_not_be_approved_again(): void
    {
        $admin = User::factory()->admin()->create();
        $solicitud = $this->makeRequest(['status' => RequestStatus::Aprobada->value]);
        $equipo = Equipment::factory()->disponible()->create(['category_id' => $solicitud->category_id]);

        $this->actingAs($admin)
            ->get(route('requests.approve', $solicitud))
            ->assertRedirect(route('requests.show', $solicitud))
            ->assertSessionHas('error');

        $this->actingAs($admin)
            ->post(route('requests.approve.store', $solicitud), [
                'equipment_ids' => [$equipo->id],
            ])
            ->assertSessionHasErrors('equipment_ids');

        $this->assertSame(RequestStatus::Aprobada, $solicitud->fresh()->status);
        $this->assertSame(0, $solicitud->items()->count());
    }

    public function test_admin_can_reject_a_request_with_a_reason(): void
    {
        $admin = User::factory()->admin()->create();
        $solicitud = $this->makeRequest();

        $this->actingAs($admin)
            ->post(route('requests.reject.store', $solicitud), [])
            ->assertSessionHasErrors('rejection_reason');

        $this->assertSame(RequestStatus::Pendiente, $solicitud->fresh()->status);

        $this->actingAs($admin)
            ->post(route('requests.reject.store', $solicitud), [
                'rejection_reason' => 'No hay equipos disponibles para esta categoría.',
            ])
            ->assertRedirect(route('requests.show', $solicitud))
            ->assertSessionHasNoErrors();

        $solicitud->refresh();

        $this->assertSame(RequestStatus::Rechazada, $solicitud->status);
        $this->assertSame('No hay equipos disponibles para esta categoría.', $solicitud->rejection_reason);
    }

    public function test_rejected_request_shows_its_reason_to_the_requester(): void
    {
        $owner = User::factory()->solicitante()->create();
        $solicitud = $this->makeRequest([
            'user' => $owner,
            'status' => RequestStatus::Rechazada->value,
        ]);
        $solicitud->update(['rejection_reason' => 'Presupuesto no disponible.']);

        $this->actingAs($owner)
            ->get(route('requests.show', $solicitud))
            ->assertOk()
            ->assertSee('Presupuesto no disponible.');
    }
}
