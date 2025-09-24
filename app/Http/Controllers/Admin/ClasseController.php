<?php

// app/Http/Controllers/Admin/ClasseController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classe;
use App\Models\Niveau;
use Illuminate\Http\Request;

class ClasseController extends Controller
{
    public function create()
    {
        $niveaux = Niveau::orderBy('nom')->get();
        return view('admin.classes.create', compact('niveaux'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'niveau_id' => 'required|exists:niveaux,id',
            'nom' => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    $niveau = Niveau::find($request->niveau_id);
                    if (!$niveau || !in_array($value, $niveau->classes_possibles)) {
                        $fail('La classe sélectionnée n\'est pas valide pour ce niveau.');
                    }
                },
            ],
        ]);

        Classe::create([
            'niveau_id' => $request->niveau_id,
            'nom' => $request->nom,
        ]);

        return redirect()->route('admin.classes.create')->with('success', 'Classe créée avec succès.');
    }
}
