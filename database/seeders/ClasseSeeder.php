<?php

namespace Database\Seeders;

use App\Models\Classe;
use App\Models\Niveau;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClasseSeeder extends Seeder
{
    // public function run(): void
    // {
    //     // Désactive les contraintes de clés étrangères temporairement
    //     DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    //     Classe::truncate(); // Supprime les anciennes classes
    //     DB::statement('SET FOREIGN_KEY_CHECKS=1;');

    //     $niveaux = ['6e', '5e', '4e', '3e', '2nde', '1ère', 'Terminale'];

    //     foreach ($niveaux as $nomNiveau) {
    //         $niveau = Niveau::where('nom', $nomNiveau)->first();

    //         if ($niveau) {
    //             Classe::create([
    //                 'nom' => 'Classe de ' . $nomNiveau,
    //                 'niveau_id' => $niveau->id,
    //             ]);
    //         } else {
    //             $this->command->warn("⚠️ Niveau '$nomNiveau' introuvable. Classe non créée.");
    //         }
    //     }

    //     $this->command->info("✅ Classes créées avec succès.");
    // }

    public function run()
    {
        // Désactive les contraintes FK temporairement pour pouvoir vider la table
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Classe::truncate();  // Vide la table classes
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $suffixes = ['A', 'B', 'C'];

        foreach (Niveau::all() as $niveau) {
            foreach ($suffixes as $suffixe) {
                Classe::create([
                    'nom' => "{$niveau->nom} {$suffixe}",
                    'niveau_id' => $niveau->id
                ]);
            }
        }
    }
}
