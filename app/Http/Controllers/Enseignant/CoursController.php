<?php

namespace App\Http\Controllers\Enseignant;

use App\Http\Controllers\Controller;
use App\Models\Classe;
use App\Models\Cours;
use App\Models\Enseignant;
use App\Models\Matiere;
use Illuminate\Http\Request;

class CoursController extends Controller
{

    public function index()
    {
        $coursList = Cours::with(['matiere', 'classe', 'enseignant'])->get();
        return view('enseignant.cours.index', compact('coursList'));
    }

    public function create()
    {
        $matieres = Matiere::all();
        $classes = Classe::all();
        $enseignants = Enseignant::all();

        return view('enseignant.cours.create', compact('matieres', 'classes', 'enseignants'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'matiere_id' => 'required|exists:matieres,id',
            'classe_id' => 'required|exists:classes,id',
            'enseignant_id' => 'required|exists:enseignants,id',
            'nom' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        Cours::create($request->all());

        return redirect()->route('cours.index')->with('success', 'Cours créé avec succès');
    }
}
