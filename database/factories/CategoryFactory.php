<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => fake()->unique()->randomElement([
                'Laptops',
                'Monitores',
                'Impresoras',
                'Computadoras de Escritorio',
                'Proyectores',
                'Escáneres',
                'Teléfonos VoIP',
                'Switches de Red',
                'All in One',
                'Tabletas',
                'Cámaras Web',
                'Reguladores de Voltaje',
            ]),
            'descripcion' => fake()->sentence(8),
        ];
    }
}
