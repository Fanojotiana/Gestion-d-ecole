<?php

namespace Database\Seeders;

use App\Models\Eleve;
use App\Models\Classe;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EleveSeeder extends Seeder
{
    public function run(): void
    {
        $faker = \Faker\Factory::create('fr_FR');

        $classes = Classe::with('niveau')->get(); // On récupère les classes avec leur niveau

        for ($i = 0; $i < 30; $i++) {
            $classe = $classes->random(); // On choisit une classe aléatoire avec son niveau

            Eleve::create([
                'nom' => $faker->lastName,
                'prenom' => $faker->firstName,
                'matricule' => strtoupper(Str::random(8)),
                'genre' => $faker->randomElement(['M', 'F']),
                'date_naissance' => $faker->dateTimeBetween('-18 years', '-6 years'),
                'adresse' => $faker->address,
                'classe_id' => $classe->id,
                'niveau_id' => $classe->niveau_id, // <-- Important
                'photo' => null, // ou un avatar par défaut
            ]);
        }
    }
}
