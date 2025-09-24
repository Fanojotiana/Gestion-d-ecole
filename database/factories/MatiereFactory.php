<?php

namespace Database\Factories;

use App\Models\Niveau;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Matiere>
 */
class MatiereFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nom' => fake()->word(),
            'niveau_id' => Niveau::inRandomOrder()->first()->id,
        ];
    }
}
