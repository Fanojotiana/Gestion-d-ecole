<?php

namespace Database\Factories;

use App\Models\Enseignant;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EnseignantFactory extends Factory
{
    protected $model = Enseignant::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(), // Crée aussi un utilisateur lié
            'nom' => $this->faker->lastName,        // 👈 Ajouté
            'prenom' => $this->faker->firstName,    // 👈 Ajouté
            'matricule' => 'ENS-' . $this->faker->unique()->numberBetween(1000, 9999),

            'grade' => $this->faker->randomElement(['Assistant', 'Maître de Conférences', 'Professeur']),
            'email' => $this->faker->unique()->safeEmail,
            'telephone' => $this->faker->phoneNumber,
            'adresse' => $this->faker->address,
            'photo' => 'default.jpg',
        ];
    }
}
