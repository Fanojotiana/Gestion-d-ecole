<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Niveau>
 */
class NiveauFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nom' => $this->faker->unique()->randomElement(['6e', '5e', '4e', '3e', '2nde', '1ère', 'Terminale']),
        ];
    }
}
