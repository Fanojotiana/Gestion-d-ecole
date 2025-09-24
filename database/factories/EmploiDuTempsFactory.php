<?php

namespace Database\Factories;

use App\Models\Classe;
use App\Models\Matiere;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EmploiDuTemps>
 */
class EmploiDuTempsFactory extends Factory
{
    public function definition(): array
    {
        return [
            'classe_id' => Classe::factory(), // crée automatiquement une classe
            'matiere_id' => 1, // à adapter selon ton projet (ajoute une factory pour Matiere si besoin)
            'jour' => $this->faker->randomElement(['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi']),
            'heure_debut' => $this->faker->time('H:i:s'),
            'heure_fin' => $this->faker->time('H:i:s'),
        ];
    }
}
