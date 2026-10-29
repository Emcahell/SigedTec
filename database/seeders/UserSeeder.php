<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed the accounts required to operate the system.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'soporte@institucion.gob.pe'],
            [
                'name' => 'Soporte Técnico',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ],
        );

        User::firstOrCreate(
            ['email' => 'jefe.oficina@institucion.gob.pe'],
            [
                'name' => 'Jefe de Oficina',
                'password' => Hash::make('password'),
                'role' => 'solicitante',
                'email_verified_at' => now(),
            ],
        );
    }
}
