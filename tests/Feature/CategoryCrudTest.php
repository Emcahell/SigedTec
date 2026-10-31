<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_list_categories(): void
    {
        Category::factory()->count(3)->create();

        $this->actingAs(User::factory()->admin()->create())
            ->get('/categories')
            ->assertOk();
    }

    public function test_admin_can_view_the_category_forms(): void
    {
        $category = Category::factory()->create();
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->get('/categories/create')->assertOk();
        $this->actingAs($admin)->get("/categories/{$category->id}/edit")->assertOk();
    }

    public function test_admin_can_create_a_category(): void
    {
        $response = $this
            ->actingAs(User::factory()->admin()->create())
            ->post('/categories', [
                'nombre' => 'Proyectores',
                'descripcion' => 'Equipos de proyección para salas de reunión.',
            ]);

        $response->assertRedirect('/categories')->assertSessionHas('success');
        $this->assertDatabaseHas('categories', ['nombre' => 'Proyectores']);
    }

    public function test_category_name_must_be_unique(): void
    {
        Category::factory()->create(['nombre' => 'Laptops']);

        $this->actingAs(User::factory()->admin()->create())
            ->post('/categories', ['nombre' => 'Laptops'])
            ->assertSessionHasErrors('nombre');
    }

    public function test_category_name_is_required(): void
    {
        $this->actingAs(User::factory()->admin()->create())
            ->post('/categories', ['nombre' => ''])
            ->assertSessionHasErrors('nombre');
    }

    public function test_admin_can_update_a_category(): void
    {
        $category = Category::factory()->create(['nombre' => 'Monitores']);

        $response = $this
            ->actingAs(User::factory()->admin()->create())
            ->put("/categories/{$category->id}", [
                'nombre' => 'Monitores y pantallas',
                'descripcion' => 'Pantallas para puestos de trabajo.',
            ]);

        $response->assertRedirect('/categories')->assertSessionHas('success');
        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'nombre' => 'Monitores y pantallas',
        ]);
    }

    public function test_admin_can_logically_delete_a_category(): void
    {
        $category = Category::factory()->create();

        $this->actingAs(User::factory()->admin()->create())
            ->delete("/categories/{$category->id}")
            ->assertRedirect('/categories')
            ->assertSessionHas('success');

        $this->assertSoftDeleted('categories', ['id' => $category->id]);

        $this->actingAs(User::factory()->admin()->create())
            ->get('/categories')
            ->assertDontSee($category->nombre);
    }

    public function test_solicitante_cannot_access_category_routes(): void
    {
        $solicitante = User::factory()->solicitante()->create();

        $this->actingAs($solicitante)->get('/categories')->assertForbidden();
        $this->actingAs($solicitante)->get('/categories/create')->assertForbidden();
    }
}
