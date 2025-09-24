<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Matiere;
use App\Models\Niveau;
use App\Models\Enseignant;
use Illuminate\Http\Request;

class MatiereController extends Controller
{
    public function index(Request $request)
    {
        $query = Matiere::with(['niveaux', 'enseignants']);

        if ($search = $request->input('search')) {
            $query->where('nom', 'like', "%{$search}%");
        }

        $matieres = $query->paginate(10);
        $niveaux = Niveau::all();
        $enseignants = Enseignant::all();

        return view('admin.matieres.index', compact('matieres', 'niveaux', 'enseignants'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'niveaux' => 'required|array',
            'niveaux.*' => 'exists:niveaux,id',
            'enseignants' => 'nullable|array',
            'enseignants.*' => 'exists:enseignants,id',
        ]);

        $matiere = Matiere::create([
            'nom' => $request->nom,
        ]);

        $matiere->niveaux()->sync($request->input('niveaux', []));

        if ($request->has('enseignants')) {
            $matiere->enseignants()->sync($request->enseignants);
        }

        return redirect()->route('admin.matieres.index')->with('success', 'Matière ajoutée avec succès.');
    }

    public function edit(Matiere $matiere)
    {
        $niveaux = Niveau::all();
        $enseignants = Enseignant::all();

        return view('admin.matieres.edit-modal', compact('matiere', 'niveaux', 'enseignants'));
    }

    public function update(Request $request, Matiere $matiere)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'niveaux' => 'required|array',
            'niveaux.*' => 'exists:niveaux,id',
            'enseignants' => 'nullable|array',
            'enseignants.*' => 'exists:enseignants,id',
        ]);

        $matiere->update([
            'nom' => $request->nom,
        ]);

        $matiere->niveaux()->sync($request->input('niveaux', []));

        if ($request->has('enseignants')) {
            $matiere->enseignants()->sync($request->enseignants);
        } else {
            $matiere->enseignants()->detach();
        }

        return redirect()->route('admin.matieres.index')->with('success', 'Matière mise à jour avec succès.');
    }

    public function destroy($id)
    {
        $matiere = Matiere::findOrFail($id);
        $matiere->enseignants()->detach();
        $matiere->niveaux()->detach();
        $matiere->delete();

        return back()->with('success', 'Matière supprimée.');
    }
}
