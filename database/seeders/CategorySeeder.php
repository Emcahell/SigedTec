<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Seed the categories used to classify the technological equipment.
     */
    public function run(): void
    {
        $categories = [
            ['nombre' => 'Laptops', 'descripcion' => 'Equipos portátiles para trabajos de oficina y movilidad.'],
            ['nombre' => 'Monitores', 'descripcion' => 'Pantallas de visualización para puestos de trabajo.'],
            ['nombre' => 'Impresoras', 'descripcion' => 'Impresoras y equipos multifuncionales.'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(['nombre' => $category['nombre']], $category);
        }
    }
}
