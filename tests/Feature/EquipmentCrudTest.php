<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Equipment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EquipmentCrudTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<string, mixed>
     */
    private function validPayload(Category $category, array $overrides = []): array
    {
        return array_merge([
            'codigo_patrimonial' => 'BI-2026-9001',
            'marca' => 'Lenovo',
            'modelo' => 'ThinkPad E14 Gen 4',
            'cpu' => 'Intel Core i5-1235U',
            'ram' => '16 GB',
            'almacenamiento' => '512 GB SSD',
            'estado' => 'disponible',
            'category_id' => $category->id,
        ], $overrides);
    }

    public function test_admin_can_list_equipment(): void
    {
        Equipment::factory()->count(3)->create();

        $this->actingAs(User::factory()->admin()->create())
            ->get('/equipments')
            ->assertOk();
    }

    public function test_admin_can_view_the_equipment_forms(): void
    {
        $equipment = Equipment::factory()->create();
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->get('/equipments/create')->assertOk();
        $this->actingAs($admin)->get("/equipments/{$equipment->id}/edit")->assertOk();
    }

    public function test_admin_can_register_an_equipment(): void
    {
        $category = Category::factory()->create();

        $response = $this
            ->actingAs(User::factory()->admin()->create())
            ->post('/equipments', $this->validPayload($category));

        $response->assertRedirect('/equipments')->assertSessionHas('success');
        $this->assertDatabaseHas('equipments', [
            'codigo_patrimonial' => 'BI-2026-9001',
            'estado' => 'disponible',
            'category_id' => $category->id,
        ]);
    }

    public function test_equipment_code_must_be_unique(): void
    {
        Equipment::factory()->create(['codigo_patrimonial' => 'BI-2026-9001']);
        $category = Category::factory()->create();

        $this->actingAs(User::factory()->admin()->create())
            ->post('/equipments', $this->validPayload($category))
            ->assertSessionHasErrors('codigo_patrimonial');
    }

    public function test_code_of_a_logically_deleted_equipment_cannot_be_reused(): void
    {
        $category = Category::factory()->create();
        Equipment::factory()->create([
            'codigo_patrimonial' => 'BI-2026-9001',
            'category_id' => $category->id,
        ])->delete();

        $this->actingAs(User::factory()->admin()->create())
            ->post('/equipments', $this->validPayload($category))
            ->assertSessionHasErrors('codigo_patrimonial');
    }

    public function test_equipment_status_must_be_valid(): void
    {
        $category = Category::factory()->create();

        $this->actingAs(User::factory()->admin()->create())
            ->post('/equipments', $this->validPayload($category, ['estado' => 'baja']))
            ->assertSessionHasErrors('estado');
    }

    public function test_equipment_category_must_exist(): void
    {
        $category = Category::factory()->create();

        $this->actingAs(User::factory()->admin()->create())
            ->post('/equipments', $this->validPayload($category, ['category_id' => 999999]))
            ->assertSessionHasErrors('category_id');
    }

    public function test_equipment_index_filters_by_category_and_status(): void
    {
        $laptops = Category::factory()->create();
        $monitores = Category::factory()->create();

        $laptop = Equipment::factory()->create([
            'category_id' => $laptops->id,
            'estado' => 'disponible',
        ]);
        $monitor = Equipment::factory()->create([
            'category_id' => $monitores->id,
            'estado' => 'asignado',
        ]);

        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get('/equipments?estado=asignado')
            ->assertOk()
            ->assertSee($monitor->codigo_patrimonial)
            ->assertDontSee($laptop->codigo_patrimonial);

        $this->actingAs($admin)
            ->get("/equipments?categoria={$laptops->id}")
            ->assertOk()
            ->assertSee($laptop->codigo_patrimonial)
            ->assertDontSee($monitor->codigo_patrimonial);

        $this->actingAs($admin)
            ->get('/equipments?categoria=999999')
            ->assertOk()
            ->assertSee('No se encontraron equipos con los filtros seleccionados.');
    }

    public function test_equipment_show_displays_its_details(): void
    {
        $equipment = Equipment::factory()->create();

        $this->actingAs(User::factory()->admin()->create())
            ->get("/equipments/{$equipment->id}")
            ->assertOk()
            ->assertSee($equipment->codigo_patrimonial)
            ->assertSee($equipment->marca);
    }

    public function test_admin_can_update_an_equipment(): void
    {
        $equipment = Equipment::factory()->create();
        $category = Category::factory()->create();

        $this->actingAs(User::factory()->admin()->create())
            ->put("/equipments/{$equipment->id}", $this->validPayload($category, [
                'codigo_patrimonial' => $equipment->codigo_patrimonial,
                'estado' => 'mantenimiento',
            ]))
            ->assertRedirect('/equipments')
            ->assertSessionHas('success');

        $this->assertDatabaseHas('equipments', [
            'id' => $equipment->id,
            'estado' => 'mantenimiento',
        ]);
    }

    public function test_admin_can_logically_delete_an_equipment(): void
    {
        $equipment = Equipment::factory()->create();

        $this->actingAs(User::factory()->admin()->create())
            ->delete("/equipments/{$equipment->id}")
            ->assertRedirect('/equipments')
            ->assertSessionHas('success');

        $this->assertSoftDeleted('equipments', ['id' => $equipment->id]);

        $this->actingAs(User::factory()->admin()->create())
            ->get('/equipments')
            ->assertDontSee($equipment->codigo_patrimonial);
    }

    public function test_solicitante_cannot_access_equipment_routes(): void
    {
        $equipment = Equipment::factory()->create();
        $solicitante = User::factory()->solicitante()->create();

        $this->actingAs($solicitante)->get('/equipments')->assertForbidden();
        $this->actingAs($solicitante)->get('/equipments/create')->assertForbidden();
        $this->actingAs($solicitante)->get("/equipments/{$equipment->id}")->assertForbidden();
    }
}
