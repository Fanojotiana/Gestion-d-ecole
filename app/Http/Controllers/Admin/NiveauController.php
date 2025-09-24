<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Niveau;
use Illuminate\Http\Request;

class NiveauController extends Controller
{
    // Afficher la liste des niveaux
    public function index(Request $request)
    {
        $query = Niveau::query();

        if ($search = $request->input('search')) {
            $query->where('nom', 'like', '%' . $search . '%');
        }

        $niveaux = $query->orderBy('nom')->paginate(10); // trié par nom, pas latest

        return view('admin.niveaux.index', compact('niveaux'));
    }


    // Afficher le formulaire de création d'un nouveau niveau
    public function create()
    {
        $niveaux = Niveau::orderBy('nom')->paginate(10);
        return view('admin.niveaux.create', compact('niveaux'));
    }


    // Enregistrer un nouveau niveau
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom_exist' => 'nullable|string|max:255|exists:niveaux,nom',
            'nom' => 'nullable|required_without:nom_exist|string|max:255|unique:niveaux,nom',
            'classes_possibles' => 'nullable|array',
            'classes_possibles.*' => 'nullable|string|max:255',
        ]);

        // Nettoyage des classes saisies (trim, ucfirst, unique)
        $classes = array_filter($validated['classes_possibles'] ?? []);
        $classes = array_map(fn($c) => ucwords(strtolower(trim($c))), $classes);
        $classes = array_unique($classes);

        // Fonction interne pour créer les classes en base
        $createClasses = function ($niveau, $classesToCreate) {
            foreach ($classesToCreate as $classeNom) {
                // Vérifier si la classe n'existe pas déjà pour ce niveau
                $exists = \App\Models\Classe::where('nom', $classeNom)
                    ->where('niveau_id', $niveau->id)
                    ->exists();

                if (!$exists) {
                    \App\Models\Classe::create([
                        'nom' => $classeNom,
                        'niveau_id' => $niveau->id,
                    ]);
                }
            }
        };

        if ($request->filled('nom_exist')) {
            // Niveau existant => mise à jour
            $niveau = \App\Models\Niveau::where('nom', $validated['nom_exist'])->first();
            $nomNiveau = strtolower($niveau->nom);

            // Vérification cohérence classes / niveau
            foreach ($classes as $classe) {
                if (!str_contains(strtolower($classe), $nomNiveau)) {
                    return back()->with('error', "La classe « $classe » ne correspond pas au niveau « {$niveau->nom} ».");
                }
            }

            $anciennes = $niveau->classes_possibles ?? [];
            $anciennes = array_map(fn($c) => ucwords(strtolower(trim($c))), $anciennes);

            $nouvelles = array_diff($classes, $anciennes);

            if (empty($nouvelles)) {
                return back()->with('info', 'Toutes les classes sont déjà associées à ce niveau.');
            }

            $toutes = array_unique(array_merge($anciennes, $nouvelles));

            // Mise à jour du champ JSON
            $niveau->update([
                'classes_possibles' => $toutes,
            ]);

            // Création des classes réelles en base
            $createClasses($niveau, $nouvelles);

            return back()->with('success', 'Nouvelles classes ajoutées au niveau existant.');
        } else {
            // Nouveau niveau
            $nomNiveau = strtolower($validated['nom']);

            foreach ($classes as $classe) {
                if (!str_contains(strtolower($classe), $nomNiveau)) {
                    return back()->with('error', "La classe « $classe » ne correspond pas au niveau « {$validated['nom']} ».");
                }
            }

            $niveau = \App\Models\Niveau::create([
                'nom' => ucwords($validated['nom']),
                'classes_possibles' => $classes,
            ]);

            // Création des classes réelles en base
            $createClasses($niveau, $classes);

            return back()->with('success', 'Nouveau niveau ajouté avec succès.');
        }
    }








    // Afficher un niveau (optionnel, parfois inutile)
    public function show(Niveau $niveau)
    {
        return view('admin.niveaux.show', compact('niveau'));
    }

    // Afficher le formulaire d'édition
    public function edit(Niveau $niveau)
    {
        return view('admin.niveaux.edit', compact('niveau'));
    }

    // Mettre à jour un niveau
     public function update(Request $request, Niveau $niveau)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255|unique:niveaux,nom,' . $niveau->id,
            'classes_possibles' => 'nullable|array',
            'classes_possibles.*' => 'nullable|string|max:255',
        ]);

        $nomNiveau = strtolower($validated['nom']);

        // Nettoyage classes
        $classes = array_filter($validated['classes_possibles'] ?? []);
        $classes = array_map(fn($c) => ucwords(strtolower(trim($c))), $classes);
        $classes = array_unique($classes);

        // Vérifier cohérence niveau/classe
        foreach ($classes as $classe) {
            if (!str_contains(strtolower($classe), $nomNiveau)) {
                return back()->with('error', "La classe « $classe » ne correspond pas au niveau « {$validated['nom']} ».");
            }
        }

        // Mise à jour du niveau (nom + classes possibles)
        $niveau->update([
            'nom' => ucwords($validated['nom']),
            'classes_possibles' => $classes,
        ]);

        // Synchronisation des classes en base (table classes)
        // Supprimer les classes existantes qui ne sont plus dans $classes
        \App\Models\Classe::where('niveau_id', $niveau->id)
            ->whereNotIn('nom', $classes)
            ->delete();

        // Ajouter les nouvelles classes (qui n'existent pas encore)
        foreach ($classes as $classeNom) {
            \App\Models\Classe::firstOrCreate([
                'niveau_id' => $niveau->id,
                'nom' => $classeNom,
            ]);
        }

        return back()->with('success', "Le niveau « {$niveau->nom} » a été mis à jour avec succès.");
    }



    // Supprimer un niveau
    public function destroy(Niveau $niveau)
    {
        $niveau->delete();

        return redirect()->route('admin.niveaux.index')
            ->with('success', 'Niveau supprimé avec succès.');
    }


}
