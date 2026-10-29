<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Equipment;
use Illuminate\Database\Seeder;

class EquipmentSeeder extends Seeder
{
    /**
     * Seed at least 20 coherent pieces of equipment.
     */
    public function run(): void
    {
        $laptops = Category::firstOrCreate(['nombre' => 'Laptops']);
        $monitores = Category::firstOrCreate(['nombre' => 'Monitores']);
        $impresoras = Category::firstOrCreate(['nombre' => 'Impresoras']);

        $equipments = [
            // Laptops -------------------------------------------------------
            ['category_id' => $laptops->id, 'codigo_patrimonial' => 'BI-2026-0001', 'marca' => 'Lenovo', 'modelo' => 'ThinkPad E14 Gen 4', 'cpu' => 'Intel Core i5-1235U', 'ram' => '16 GB', 'almacenamiento' => '512 GB SSD', 'estado' => 'disponible'],
            ['category_id' => $laptops->id, 'codigo_patrimonial' => 'BI-2026-0002', 'marca' => 'HP', 'modelo' => 'ProBook 450 G9', 'cpu' => 'Intel Core i5-1235U', 'ram' => '8 GB', 'almacenamiento' => '256 GB SSD', 'estado' => 'disponible'],
            ['category_id' => $laptops->id, 'codigo_patrimonial' => 'BI-2026-0003', 'marca' => 'Dell', 'modelo' => 'Latitude 5420', 'cpu' => 'Intel Core i7-1165G7', 'ram' => '16 GB', 'almacenamiento' => '512 GB SSD', 'estado' => 'asignado'],
            ['category_id' => $laptops->id, 'codigo_patrimonial' => 'BI-2026-0004', 'marca' => 'Acer', 'modelo' => 'Aspire 5 A515-57', 'cpu' => 'Intel Core i5-12450H', 'ram' => '8 GB', 'almacenamiento' => '256 GB SSD', 'estado' => 'disponible'],
            ['category_id' => $laptops->id, 'codigo_patrimonial' => 'BI-2026-0005', 'marca' => 'Lenovo', 'modelo' => 'IdeaPad 3 15ITL6', 'cpu' => 'Intel Core i3-1115G4', 'ram' => '8 GB', 'almacenamiento' => '256 GB SSD', 'estado' => 'mantenimiento'],
            ['category_id' => $laptops->id, 'codigo_patrimonial' => 'BI-2026-0006', 'marca' => 'HP', 'modelo' => '250 G9', 'cpu' => 'Intel Core i5-1235U', 'ram' => '8 GB', 'almacenamiento' => '512 GB SSD', 'estado' => 'disponible'],
            ['category_id' => $laptops->id, 'codigo_patrimonial' => 'BI-2026-0007', 'marca' => 'Dell', 'modelo' => 'Inspiron 15 3520', 'cpu' => 'Intel Core i3-1215U', 'ram' => '8 GB', 'almacenamiento' => '1 TB HDD', 'estado' => 'asignado'],
            ['category_id' => $laptops->id, 'codigo_patrimonial' => 'BI-2026-0008', 'marca' => 'Asus', 'modelo' => 'VivoBook 15 X1502ZA', 'cpu' => 'Intel Core i5-1240P', 'ram' => '16 GB', 'almacenamiento' => '512 GB SSD', 'estado' => 'disponible'],

            // Monitores (sin componentes de cómputo) ------------------------
            ['category_id' => $monitores->id, 'codigo_patrimonial' => 'BI-2026-0009', 'marca' => 'Dell', 'modelo' => 'P2422H 24"', 'cpu' => null, 'ram' => null, 'almacenamiento' => null, 'estado' => 'asignado'],
            ['category_id' => $monitores->id, 'codigo_patrimonial' => 'BI-2026-0010', 'marca' => 'HP', 'modelo' => 'P24h G4 23.8"', 'cpu' => null, 'ram' => null, 'almacenamiento' => null, 'estado' => 'disponible'],
            ['category_id' => $monitores->id, 'codigo_patrimonial' => 'BI-2026-0011', 'marca' => 'LG', 'modelo' => '24MK430H 23.6"', 'cpu' => null, 'ram' => null, 'almacenamiento' => null, 'estado' => 'disponible'],
            ['category_id' => $monitores->id, 'codigo_patrimonial' => 'BI-2026-0012', 'marca' => 'Samsung', 'modelo' => 'S24R350 23.8"', 'cpu' => null, 'ram' => null, 'almacenamiento' => null, 'estado' => 'asignado'],
            ['category_id' => $monitores->id, 'codigo_patrimonial' => 'BI-2026-0013', 'marca' => 'BenQ', 'modelo' => 'GW2480 23.8"', 'cpu' => null, 'ram' => null, 'almacenamiento' => null, 'estado' => 'mantenimiento'],
            ['category_id' => $monitores->id, 'codigo_patrimonial' => 'BI-2026-0014', 'marca' => 'Lenovo', 'modelo' => 'ThinkVision E24-30 23.8"', 'cpu' => null, 'ram' => null, 'almacenamiento' => null, 'estado' => 'disponible'],

            // Impresoras (sin componentes de cómputo) -----------------------
            ['category_id' => $impresoras->id, 'codigo_patrimonial' => 'BI-2026-0015', 'marca' => 'Canon', 'modelo' => 'i-SENSYS LBP226dw', 'cpu' => null, 'ram' => null, 'almacenamiento' => null, 'estado' => 'disponible'],
            ['category_id' => $impresoras->id, 'codigo_patrimonial' => 'BI-2026-0016', 'marca' => 'HP', 'modelo' => 'LaserJet Pro M140w', 'cpu' => null, 'ram' => null, 'almacenamiento' => null, 'estado' => 'asignado'],
            ['category_id' => $impresoras->id, 'codigo_patrimonial' => 'BI-2026-0017', 'marca' => 'Epson', 'modelo' => 'EcoTank L3250', 'cpu' => null, 'ram' => null, 'almacenamiento' => null, 'estado' => 'disponible'],
            ['category_id' => $impresoras->id, 'codigo_patrimonial' => 'BI-2026-0018', 'marca' => 'Brother', 'modelo' => 'HL-L2350DW', 'cpu' => null, 'ram' => null, 'almacenamiento' => null, 'estado' => 'disponible'],
            ['category_id' => $impresoras->id, 'codigo_patrimonial' => 'BI-2026-0019', 'marca' => 'Kyocera', 'modelo' => 'ECOSYS M2040dn', 'cpu' => null, 'ram' => null, 'almacenamiento' => null, 'estado' => 'mantenimiento'],
            ['category_id' => $impresoras->id, 'codigo_patrimonial' => 'BI-2026-0020', 'marca' => 'Canon', 'modelo' => 'imageCLASS MF3010', 'cpu' => null, 'ram' => null, 'almacenamiento' => null, 'estado' => 'disponible'],
        ];

        foreach ($equipments as $equipment) {
            Equipment::firstOrCreate(
                ['codigo_patrimonial' => $equipment['codigo_patrimonial']],
                $equipment,
            );
        }
    }
}
