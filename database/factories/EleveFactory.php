<?php

namespace Database\Factories;

use App\Models\Classe;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Eleve>
 */
class EleveFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nom' => $this->faker->lastName,
            'prenom' => $this->faker->firstName,
            'classe_id' => Classe::factory(), // ou directement un ID si tu préfères
        ];
    }
}
