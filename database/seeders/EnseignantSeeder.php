<?php

namespace Database\Seeders;

use App\Models\Enseignant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EnseignantSeeder extends Seeder
{
    public function run()
    {
        Enseignant::factory()->count(50)->create();
    }
}
