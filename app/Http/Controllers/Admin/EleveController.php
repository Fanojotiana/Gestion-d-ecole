<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Eleve;
use App\Models\Classe;
use App\Models\Niveau;
use App\Models\Note;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EleveController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $classeId = $request->input('classe_id');

        $eleves = Eleve::with('classe.niveau')
            ->when($classeId, function ($query) use ($classeId) {
                $query->where('classe_id', $classeId);
            })
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nom', 'like', "%{$search}%")
                        ->orWhere('prenom', 'like', "%{$search}%")
                        ->orWhere('matricule', 'like', "%{$search}%");
                });
            })
            ->orderBy('nom')
            ->orderBy('prenom')
            ->paginate(10);

        $eleves->appends([
            'search' => $search,
            'classe_id' => $classeId,
        ]);

        $classes = Classe::all();
        $niveaux = Niveau::all();

        return view('admin.eleves.index', compact('eleves', 'search', 'classes', 'niveaux', 'classeId'));
    }




    public function create()
    {
        $classes = Classe::with('niveau')->get(); // avec relation niveau pour affichage dans option
        $niveaux = Niveau::all();  // Récupérer tous les niveaux
        return view('admin.eleves.create', compact('classes', 'niveaux'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'matricule' => 'required|string|unique:eleves,matricule',
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'genre' => 'required|in:M,F',
            'date_naissance' => 'nullable|date',
            'adresse' => 'nullable|string',
            'niveau_id' => 'required|exists:niveaux,id',
            'classe_id' => 'required|exists:classes,id',
            'urgence_contact' => 'nullable|string|max:255',
            'photo' => 'nullable|image|max:2048',
        ]);

        $niveau = Niveau::find($validated['niveau_id']);
        $classe = Classe::find($validated['classe_id']);

        if (!$niveau || !$classe) {
            return back()->withInput()->withErrors(['classe_id' => 'Classe ou niveau invalide.']);
        }

        // Fonction pour extraire le "numéro" du niveau (ex: "6e A" -> "6e", "Terminale B" -> "terminale")
        function extractNiveauNumber(string $nom)
        {
            if (preg_match('/^[^\s]+/', $nom, $matches)) {
                return strtolower($matches[0]);
            }
            return strtolower($nom);
        }

        $niveauNumero = extractNiveauNumber($niveau->nom);  // ex: "6e"
        $classeNumero = extractNiveauNumber($classe->nom);  // ex: "6e"

        // Vérifier que la classe commence par le niveau (ex: "6e A" commence par "6e")
        if (!str_starts_with($classeNumero, $niveauNumero)) {
            return back()->withInput()->withErrors([
                'classe_id' => 'La classe choisie ne correspond pas au niveau sélectionné.'
            ]);
        }

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('photos/eleves', 'public');
        }

        Eleve::create([
            'matricule' => $validated['matricule'],
            'nom' => $validated['nom'],
            'prenom' => $validated['prenom'],
            'genre' => $validated['genre'],
            'date_naissance' => $validated['date_naissance'],
            'adresse' => $validated['adresse'],
            'niveau_id' => $niveau->id,
            'classe_id' => $classe->id,
            'urgence_contact' => $validated['urgence_contact'],
            'photo' => $photoPath,
        ]);

        return redirect()->route('admin.eleves.index')->with('success', 'Élève ajouté avec succès.');
    }









    public function edit(Eleve $eleve)
    {
        $classes = Classe::with('niveau')->get(); // pour pouvoir afficher le niveau associé à chaque classe
        $niveaux = Niveau::all(); // récupérer tous les niveaux
        return view('admin.eleves.edit-modal', compact('eleve', 'classes', 'niveaux'));
    }


    public function update(Request $request, Eleve $eleve)
    {
        $validated = $request->validate([
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'matricule' => 'required|string|unique:eleves,matricule,' . $eleve->id,
            'genre' => 'required|in:M,F',
            'date_naissance' => 'nullable|date',
            'adresse' => 'nullable|string',
            'urgence_contact' => 'nullable|string|max:255',
            'niveau_id' => 'required|exists:niveaux,id',
            'classe_id' => 'required|exists:classes,id',
            'photo' => 'nullable|image|max:2048',
        ]);

        // Vérifier correspondance entre niveau et classe
        $niveau = \App\Models\Niveau::find($validated['niveau_id']);
        $classe = \App\Models\Classe::with('niveau')->find($validated['classe_id']);

        if (!$classe || !$niveau) {
            return back()->withInput()->withErrors(['classe_id' => 'Classe ou niveau invalide.']);
        }

        // Fonction utilitaire pour extraire la base du niveau (ex: "6e" depuis "6e A")
        function extractNiveauPart($nom)
        {
            if (preg_match('/^[^\s]+/', $nom, $matches)) {
                return strtolower($matches[0]);
            }
            return strtolower($nom);
        }

        $niveauNom = extractNiveauPart($niveau->nom);
        $classeNom = extractNiveauPart($classe->nom);

        if ($classeNom !== $niveauNom) {
            return back()->withInput()->withErrors([
                'classe_id' => 'La classe choisie ne correspond pas au niveau sélectionné.'
            ]);
        }

        // Upload de photo si fournie
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('photos/eleves', 'public');
            $validated['photo'] = $photoPath;
        } else {
            // Si pas de nouvelle photo, garder l'ancienne
            $validated['photo'] = $eleve->photo;
        }

        // Mise à jour de l’élève
        $eleve->update($validated);

        return redirect()->route('admin.eleves.index')->with('success', 'Élève mis à jour avec succès.');
    }



    public function destroy(Eleve $eleve)
    {
        if ($eleve->photo) {
            Storage::disk('public')->delete($eleve->photo);
        }

        $eleve->delete();
        return redirect()->route('admin.eleves.index')->with('success', 'Élève supprimé.');
    }


    public function exportBulletinPdf($eleveId)
    {
        $eleve = Eleve::findOrFail($eleveId);

        // Récupérer les notes par trimestre, etc. (à adapter à ta logique)
        $notes = Note::where('eleve_id', $eleveId)
            ->whereIn('periode', ['Trimestre 1', 'Trimestre 2', 'Trimestre 3'])
            ->with('matiere')
            ->get()
            ->groupBy('periode');

        // Calcul des moyennes (comme vu avant)
        $moyennes = [];
        foreach (['Trimestre 1', 'Trimestre 2', 'Trimestre 3'] as $periode) {
            if (isset($notes[$periode])) {
                $moyennes[$periode] = $this->moyennePonderee($notes[$periode]);
            } else {
                $moyennes[$periode] = 0;
            }
        }

        $moyenneAnnuelle = round(array_sum($moyennes) / count($moyennes), 2);

        // Génère le PDF à partir d'une vue blade dédiée
        $pdf = Pdf::loadView('admin.eleves.bulletin_pdf', compact('eleve', 'notes', 'moyennes', 'moyenneAnnuelle'));

        return $pdf->download("bulletin_{$eleve->nom}_{$eleve->prenom}.pdf");
    }

    private function moyennePonderee($notes)
    {
        $totalPondere = 0;
        $totalCoef = 0;
        foreach ($notes as $note) {
            $totalPondere += $note->note * $note->coefficient;
            $totalCoef += $note->coefficient;
        }
        if ($totalCoef == 0) return 0;
        return round($totalPondere / $totalCoef, 2);
    }
}
