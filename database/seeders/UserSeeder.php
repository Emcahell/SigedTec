<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed the administrator account required to operate the system.
     * Requester accounts are created from the administration panel.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@sigedtec.com'],
            [
                'name' => 'Administrador SIGEDTEC',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ],
        );
    }
}
