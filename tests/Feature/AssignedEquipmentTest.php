<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Equipment;
use App\Models\Request;
use App\Models\RequestItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AssignedEquipmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_solicitante_sees_only_their_assigned_equipment(): void
    {
        $owner = User::factory()->solicitante()->create();
        $other = User::factory()->solicitante()->create();
        $category = Category::factory()->create(['nombre' => 'Laptops']);

        $ownEquipment = Equipment::factory()->asignado()->create([
            'category_id' => $category->id,
            'codigo_patrimonial' => 'BI-111111',
        ]);
        $otherEquipment = Equipment::factory()->asignado()->create([
            'category_id' => $category->id,
            'codigo_patrimonial' => 'BI-222222',
        ]);

        $ownRequest = Request::create([
            'user_id' => $owner->id,
            'office' => 'Oficina de Tesorería',
            'category_id' => $category->id,
            'quantity' => 1,
            'status' => 'aprobada',
        ]);

        $otherRequest = Request::create([
            'user_id' => $other->id,
            'office' => 'Oficina de Logística',
            'category_id' => $category->id,
            'quantity' => 1,
            'status' => 'aprobada',
        ]);

        RequestItem::create([
            'request_id' => $ownRequest->id,
            'equipment_id' => $ownEquipment->id,
            'assigned_at' => now(),
        ]);

        RequestItem::create([
            'request_id' => $otherRequest->id,
            'equipment_id' => $otherEquipment->id,
            'assigned_at' => now(),
        ]);

        $this->actingAs($owner)
            ->get(route('equipments.mine'))
            ->assertOk()
            ->assertSee('BI-111111')
            ->assertDontSee('BI-222222');
    }

    public function test_solicitante_without_assignments_sees_an_empty_state(): void
    {
        $user = User::factory()->solicitante()->create();

        $this->actingAs($user)
            ->get(route('equipments.mine'))
            ->assertOk()
            ->assertSee('Aún no tiene equipos asignados.');
    }

    public function test_admin_cannot_access_the_requester_equipment_section(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get(route('equipments.mine'))
            ->assertForbidden();
    }
}
