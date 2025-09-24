<?php

namespace Database\Factories;

use App\Models\Niveau;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Classe>
 */
class ClasseFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nom' => $this->faker->randomElement([
                '6e A',
                '6e B',
                '5e A',
                '5e B',
                '4e A',
                '3e A',
            ]),
            'niveau_id' => Niveau::factory(), // 🔥 Ceci évite l'erreur SQL
        ];
    }
}
