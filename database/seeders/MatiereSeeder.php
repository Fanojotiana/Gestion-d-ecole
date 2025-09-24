<?php

namespace Database\Seeders;

use App\Models\Matiere;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MatiereSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $matieres = [
            'Mathématiques' => ['6e', '5e', '4e', '3e', '2nde', '1ère', 'Terminale'],
            'Français' => ['6e', '5e', '4e', '3e', '2nde', '1ère', 'Terminale'],
            'Anglais' => ['6e', '5e', '4e', '3e', '2nde', '1ère', 'Terminale'],
            'Histoire-Géographie' => ['6e', '5e', '4e', '3e', '2nde', '1ère', 'Terminale'],
            'SVT' => ['6e', '5e', '4e', '3e', '2nde', '1ère', 'Terminale'],
            'Physique-Chimie' => ['4e', '3e', '2nde', '1ère', 'Terminale'],
            'Technologie' => ['6e', '5e', '4e', '3e'],
            'EPS' => ['6e', '5e', '4e', '3e', '2nde', '1ère', 'Terminale'],
            'Philosophie' => ['Terminale'],
            'SES' => ['2nde', '1ère', 'Terminale'],
            'Informatique' => ['2nde', '1ère', 'Terminale'],
        ];

        foreach ($matieres as $nom => $niveaux) {
            $matiere = Matiere::create(['nom' => $nom]);

            foreach ($niveaux as $niveauNom) {
                $niveau = \App\Models\Niveau::where('nom', $niveauNom)->first();
                if ($niveau) {
                    $matiere->niveaux()->attach($niveau->id);
                }
            }
        }
    }
}
