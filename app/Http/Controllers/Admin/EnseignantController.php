<?php

namespace App\Http\Controllers\Admin;

use App\Models\Classe;
use App\Models\Matiere;
use App\Models\User;
use App\Models\Enseignant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class EnseignantController extends Controller
{
    public function index(Request $request)
    {
        $query = Enseignant::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'LIKE', "%{$search}%")
                    ->orWhere('prenom', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        $enseignants = $query->paginate(10);
        $allMatieres = Matiere::all(); // Charger toutes les matières
        // dd($enseignants);


        return view('admin.enseignants.index', compact('enseignants', 'allMatieres'));
    }

    public function create()
    {
        $classes = Classe::all();
        $matieres = Matiere::all();
        return view('admin.enseignants.create', compact( 'matieres'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'matricule' => 'required|string|unique:enseignants,matricule',
            'matieres' => 'required|array',
            'matieres.*' => 'exists:matieres,id',
            'grade' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email',
            'telephone' => 'nullable|string|max:50',
            'adresse' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'password' => 'required|min:6',
        ]);

        // Création du compte utilisateur lié
        $user = User::create([
            'nom' => $validated['nom'],
            'prenom' => $validated['prenom'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'enseignant',
            'pseudo' => strtolower($validated['prenom']) . rand(100, 999),
        ]);

        // Gestion de la photo
        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('enseignants/photos', 'public');
        }

        $validated['user_id'] = $user->id;

        // On retire "matieres" pour éviter un champ inconnu dans la table `enseignants`
        $enseignantData = collect($validated)->except('matieres')->toArray();

        // Création de l'enseignant
        $enseignant = Enseignant::create($enseignantData);

        // Liaison enseignant <-> matières (table pivot)
        $enseignant->matieres()->sync($validated['matieres']);

        return redirect()->route('admin.enseignants.index')->with('success', 'Enseignant ajouté avec succès et matières associées.');
    }


    public function show(Enseignant $enseignant)
    {
        return view('admin.enseignant.show', compact('enseignant'));
    }

    public function edit($id)
    {
        $enseignant = Enseignant::findOrFail($id);
        $classes = Classe::all();
        $allMatieres = Matiere::all(); // récupère toutes les matières

        return view('admin.enseignants.edit', compact('enseignant', 'classes', 'allMatieres'));
    }

    public function update(Request $request, $id)
    {
        $enseignant = Enseignant::findOrFail($id);

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'matricule' => 'required|string|max:255|unique:enseignants,matricule,' . $enseignant->id,
            'matieres' => 'required|array',
            'matieres.*' => 'exists:matieres,id',
            'grade' => 'nullable|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $enseignant->user_id,
            'telephone' => 'required|string|max:20',
            'adresse' => 'nullable|string|max:255',
            'photo' => 'nullable|image|max:2048',
        ]);

        // Mise à jour de l'utilisateur lié
        $user = $enseignant->user;

        if ($user) {
            $user->nom = $validated['nom'];
            $user->prenom = $validated['prenom'];
            $user->email = $validated['email'];
            $user->save();
        } else {
            // Optionnel : gérer le cas où il n'y a pas de user lié
            // Par exemple créer un user ou lancer une exception
            return redirect()->back()->withErrors('Utilisateur lié introuvable.');
        }


        // Gestion de la photo
        if ($request->hasFile('photo')) {
            if ($enseignant->photo && Storage::disk('public')->exists($enseignant->photo)) {
                Storage::disk('public')->delete($enseignant->photo);
            }
            $enseignant->photo = $request->file('photo')->store('enseignants/photos', 'public');
        }

        // Mise à jour des données de l'enseignant
        $enseignant->update([
            'nom' => $validated['nom'],
            'prenom' => $validated['prenom'],
            'matricule' => $validated['matricule'],
            'grade' => $validated['grade'] ?? null,
            'email' => $validated['email'],
            'telephone' => $validated['telephone'],
            'adresse' => $validated['adresse'] ?? null,
            'photo' => $enseignant->photo,
        ]);

        // Synchronisation des matières
        $enseignant->matieres()->sync($validated['matieres']);

        return redirect()->route('admin.enseignants.index')->with('success', 'Enseignant modifié avec succès.');
    }


    public function destroy($id)
    {
        $enseignant = Enseignant::findOrFail($id);

        if ($enseignant->photo && Storage::disk('public')->exists($enseignant->photo)) {
            Storage::disk('public')->delete($enseignant->photo);
        }

        $enseignant->delete();

        return redirect()->route('admin.enseignants.index')->with('success', 'Enseignant supprimé avec succès.');
    }
}
