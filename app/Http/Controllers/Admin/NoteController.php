<?php

// app/Http/Controllers/Admin/NoteController.php

namespace App\Http\Controllers\Admin;

use App\Models\Classe;
use App\Models\Note;
use App\Models\Eleve;
use App\Models\Matiere;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class NoteController extends Controller
{
    public function index(Request $request)
    {
        // Récupérer les variables de filtre
        $classeId = $request->classe_id;
        $matiereId = $request->matiere_id;
        $periode = $request->periode;

        // Récupérer toutes les notes filtrées selon les critères, avec relations
        $notes = Note::with('eleve', 'matiere')
            ->when($request->classe_id, fn($q) => $q->whereHas('eleve', fn($q2) => $q2->where('classe_id', $request->classe_id)))
            ->when($request->matiere_id, fn($q) => $q->where('matiere_id', $request->matiere_id))
            ->when($request->periode, fn($q) => $q->where('periode', $request->periode))
            ->get();

        $groupedNotes = $notes->groupBy('eleve_id');


        // Récupérer les classes et matières pour les filtres
        $classes = Classe::all();
        $matieres = Matiere::all();

        // Passer les données à la vue
        return view('admin.notes.index', compact('groupedNotes', 'classes', 'matieres', 'classeId', 'matiereId', 'periode'));
    }










    public function create()
    {
        $eleves = Eleve::all();
        $classes = Classe::all();
        $matieres = Matiere::all();
        return view('admin.notes.create', compact('eleves', 'matieres', 'classes'));
    }




    public function store(Request $request)
    {
        $request->validate([
            'eleve_id' => ['required', 'exists:eleves,id'],
            'matiere_id' => ['required', 'exists:matieres,id'],
            'note' => ['required', 'numeric', 'min:0', 'max:20'],
            'coefficient' => ['required', 'numeric', 'min:1'],
            'periode' => ['required', Rule::in(['Trimestre 1', 'Trimestre 2', 'Trimestre 3'])],

            // Clé unique sur la combinaison eleve_id, matiere_id, periode
            'periode' => [
                'required',
                Rule::in(['Trimestre 1', 'Trimestre 2', 'Trimestre 3']),
                Rule::unique('notes')->where(function ($query) use ($request) {
                    return $query->where('eleve_id', $request->eleve_id)
                        ->where('matiere_id', $request->matiere_id);
                }),
            ],
        ], [
            'periode.unique' => 'Une note pour cet élève, cette matière et cette période existe déjà.',
        ]);

        Note::create($request->all());

        return redirect()->route('admin.notes.index')->with('success', 'Note ajoutée avec succès.');
    }



    public function edit(Note $note)
    {
        $eleves = Eleve::all();
        $matieres = Matiere::all();
        return view('admin.notes.edit', compact('note', 'eleves', 'matieres'));
    }

    public function update(Request $request, Note $note)
    {
        $validated = $request->validate([
            'classe_id' => 'required|exists:classes,id',
            'eleve_id' => 'required|exists:eleves,id',
            'matiere_id' => 'required|exists:matieres,id',
            'note' => 'required|numeric|min:0|max:20',
            'coefficient' => 'required|numeric|min:0',
            'periode' => 'required|string',
            'commentaire' => 'nullable|string',
        ]);

        // Ici on met à jour la note existante
        $note->update($validated);

        return redirect()->route('admin.notes.index')->with('success', 'Note mise à jour avec succès.');
    }

    public function updateMultiple(Request $request, $eleveId)
    {
        $data = $request->input('notes', []);

        foreach ($data as $noteData) {
            $note = Note::find($noteData['id']);
            if ($note && $note->eleve_id == $eleveId) {
                $note->update([
                    'note' => $noteData['note'],
                    'coefficient' => $noteData['coefficient'],
                    'periode' => $noteData['periode'],
                    'commentaire' => $noteData['commentaire'] ?? null,
                ]);
            }
        }

        return redirect()->route('admin.notes.index')->with('success', 'Notes mises à jour avec succès.');
    }




    public function destroy(Note $note)
    {
        $note->delete();
        return back()->with('success', 'Note supprimée.');
    }
    public function getEleves($classeId)
    {
        $eleves = Eleve::where('classe_id', $classeId)->get(['id', 'nom', 'prenom']);
        return response()->json($eleves);
    }

    public function exportBulletinPdf($eleveId, $periode = 'Trimestre 1')
    {
        $eleve = Eleve::with(['notes' => function ($query) use ($periode) {
            $query->where('periode', $periode)->with('matiere')->orderBy('matiere_id');
        }])->findOrFail($eleveId);

        $notes = $eleve->notes;

        $pdf = Pdf::loadView('admin.notes.Note-trimestre', compact('eleve', 'notes', 'periode'));

        return $pdf->download('bulletin_' . $eleve->nom . '_' . $periode . '.pdf');
    }



    public function rangParClasse(Request $request)
    {
        $classeId = $request->input('classe_id');
        $periode = $request->input('periode', 'Trimestre 1');

        // Récupérer les élèves avec les notes pour la période sélectionnée (filtrage facultatif sur classe)
        $eleves = Eleve::with(['classe', 'notes' => function ($query) use ($periode) {
            $query->where('periode', $periode);
        }])
            ->when($classeId, function ($query) use ($classeId) {
                $query->where('classe_id', $classeId);
            })
            ->get();

        // Calcul de la moyenne par élève
        $elevesAvecMoyennes = $eleves->map(function ($eleve) {
            $somme = 0;
            $totalCoef = 0;

            foreach ($eleve->notes as $note) {
                $somme += $note->note * $note->coefficient;
                $totalCoef += $note->coefficient;
            }

            $moyenne = $totalCoef > 0 ? $somme / $totalCoef : 0;
            $eleve->moyenne = round($moyenne, 2);

            return $eleve;
        });

        // Tri décroissant des moyennes
        $elevesTries = $elevesAvecMoyennes->sortByDesc('moyenne')->values();

        // Attribution du rang et du tableau d'honneur
        $elevesTries->each(function ($eleve, $index) {
            $eleve->rang = $index + 1;
            $eleve->tableau_honneur = $eleve->moyenne >= 15;
        });

        // Liste des classes pour les filtres
        $classes = Classe::all();

        return view('admin.notes.rang', [
            'eleves' => $elevesTries,
            'classeId' => $classeId,
            'periode' => $periode,
            'classes' => $classes,
        ]);
    }
}
