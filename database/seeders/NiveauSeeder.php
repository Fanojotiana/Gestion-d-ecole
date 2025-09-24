<?php

namespace Database\Seeders;

use App\Models\Niveau;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NiveauSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    // public function run(): void
    // {
    //     DB::table('niveaux')->insert([
    //         ['nom' => '6ème', 'classes_possibles' => json_encode(['6A', '6B', '6C'])],
    //         ['nom' => '5ème', 'classes_possibles' => json_encode(['5A', '5B'])],
    //         ['nom' => '4ème', 'classes_possibles' => json_encode(['4A', '4B'])],

    //         // ajoute tes niveaux ici
    //     ]);
    // }

    public function run()
    {
        $niveaux = ['6e', '5e', '4e', '3e', '2nde', '1ère', 'Terminale'];

        foreach ($niveaux as $nom) {
            Niveau::firstOrCreate(['nom' => $nom]);
        }
    }
}
