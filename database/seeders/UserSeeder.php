<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::updateOrCreate([
            'pseudo' => 'admin',
            'email' => 'fanojo@example.com',
            'password' => Hash::make('password'), // Choisis un mot de passe sécurisé
            'role' => 'admin',
        ]);

        User::factory()->count(50)->create();
    }
}
