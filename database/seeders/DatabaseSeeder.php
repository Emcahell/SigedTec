<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database with the administrator account only.
     * Categories, equipment and requester accounts are managed from the
     * administration panel.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
        ]);
    }
}
