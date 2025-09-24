<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Responsable;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ResponsableController extends Controller
{
    public function index()
    {
        $responsables = User::where('role', 'responsable')->paginate(10);
        return view('admin.responsable.index', compact('responsables'));
    }

    public function create()
    {
        return view('admin.responsable.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'date_naissance' => 'nullable|date',
            'sexe' => 'nullable|string|max:10',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'adresse' => 'nullable|string',
            'telephone' => 'nullable|string|max:50',
            'email' => 'required|email|unique:users,email',
            'responsable_type' => 'required|string|max:255',
            'password' => 'required|min:6',
        ]);
        // dd($validated);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('responsables/photos', 'public');
        }

        // Création utilisateur
        $user = User::create([
            'nom' => $validated['nom'],
            'prenom' => $validated['prenom'],
            'date_naissance' => $validated['date_naissance'] ?? null,
            'sexe' => $validated['sexe'] ?? null,
            'photo' => $validated['photo'] ?? null,
            'adresse' => $validated['adresse'] ?? null,
            'telephone' => $validated['telephone'] ?? null,
            'email' => $validated['email'],
            'role' => 'responsable',
            'password' => Hash::make($validated['password']),
            'pseudo' => strtolower($validated['prenom']) . rand(100, 999),
        ]);

        // Création responsable lié
        Responsable::create([
            'user_id' => $user->id,
            'responsable_type' => $validated['responsable_type'],
        ]);

        return redirect()->route('admin.responsables.create')->with('success', 'Responsable ajouté avec succès.');
    }



    public function edit($id)
    {
        $responsable = User::where('role', 'responsable')->findOrFail($id);
        return view('admin.responsable.edit', compact('responsable'));
    }

    public function update(Request $request, $id)
    {
        $responsable = User::where('role', 'responsable')->findOrFail($id);

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'date_naissance' => 'nullable|date',
            'sexe' => 'nullable|string|max:10',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'adresse' => 'nullable|string',
            'telephone' => 'nullable|string|max:50',
            'email' => 'required|email|unique:users,email,' . $responsable->id,
            'responsable_type' => 'required|string|max:255',
            'password' => 'nullable|min:6|confirmed',
        ]);

        if ($request->hasFile('photo')) {
            if ($responsable->photo && Storage::disk('public')->exists($responsable->photo)) {
                Storage::disk('public')->delete($responsable->photo);
            }
            $validated['photo'] = $request->file('photo')->store('responsables/photos', 'public');
        }

        $responsable->nom = $validated['nom'];
        $responsable->prenom = $validated['prenom'];
        $responsable->date_naissance = $validated['date_naissance'] ?? null;
        $responsable->sexe = $validated['sexe'] ?? null;
        $responsable->photo = $validated['photo'] ?? $responsable->photo;
        $responsable->adresse = $validated['adresse'] ?? null;
        $responsable->telephone = $validated['telephone'] ?? null;
        $responsable->email = $validated['email'];
        $responsable->responsable_type = $validated['responsable_type'];

        if (!empty($validated['password'])) {
            $responsable->password = Hash::make($validated['password']);
        }

        $responsable->save();

        return redirect()->route('admin.responsables.index')->with('success', 'Responsable modifié avec succès.');
    }

    public function destroy($id)
    {
        $responsable = User::where('role', 'responsable')->findOrFail($id);

        if ($responsable->photo) {
            Storage::disk('public')->delete($responsable->photo);
        }

        $responsable->delete();

        return redirect()->route('admin.responsables.index')->with('success', 'Responsable supprimé avec succès.');
    }
}
