<?php

namespace Database\Factories;

use App\Enums\EquipmentStatus;
use App\Models\Category;
use App\Models\Equipment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Equipment>
 */
class EquipmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'codigo_patrimonial' => 'BI-'.fake()->unique()->numberBetween(100000, 999999),
            'marca' => fake()->randomElement([
                'Lenovo',
                'HP',
                'Dell',
                'Acer',
                'Asus',
                'Canon',
                'Epson',
                'LG',
                'Samsung',
                'BenQ',
                'Kyocera',
            ]),
            'modelo' => strtoupper(fake()->bothify('??-###')),
            'cpu' => fake()->randomElement([
                'Intel Core i3-1215U',
                'Intel Core i5-1235U',
                'Intel Core i7-1165G7',
                'AMD Ryzen 5 5600G',
            ]),
            'ram' => fake()->randomElement(['8 GB', '16 GB', '32 GB']),
            'almacenamiento' => fake()->randomElement([
                '256 GB SSD',
                '512 GB SSD',
                '1 TB SSD',
                '1 TB HDD',
            ]),
            'estado' => EquipmentStatus::Disponible->value,
            'category_id' => Category::factory(),
        ];
    }

    /**
     * Indicate that the equipment is available.
     */
    public function disponible(): static
    {
        return $this->state(fn (array $attributes) => [
            'estado' => EquipmentStatus::Disponible->value,
        ]);
    }

    /**
     * Indicate that the equipment is assigned to an office.
     */
    public function asignado(): static
    {
        return $this->state(fn (array $attributes) => [
            'estado' => EquipmentStatus::Asignado->value,
        ]);
    }

    /**
     * Indicate that the equipment is under maintenance.
     */
    public function mantenimiento(): static
    {
        return $this->state(fn (array $attributes) => [
            'estado' => EquipmentStatus::Mantenimiento->value,
        ]);
    }
}
