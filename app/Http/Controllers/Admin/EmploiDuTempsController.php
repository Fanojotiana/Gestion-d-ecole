<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmploiDuTemps;
use App\Models\Matiere;
use App\Models\Enseignant;
use App\Models\Classe;
use App\Models\Niveau;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;




class EmploiDuTempsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Affiche l'emploi du temps sous forme de tableau hebdomadaire
     */
    public function index(Request $request)
    {
        $classeId = $request->query('classe_id');
        $semaine = $request->query('semaine');

        // Requête principale avec relations
        $query = EmploiDuTemps::with('matiere', 'enseignant');

        // Filtrage par classe
        if ($classeId) {
            $query->where('classe_id', $classeId);
        }

        // Filtrage par semaine
        if ($semaine) {
            $query->where('semaine', $semaine);
        }

        // Exécution de la requête
        $emploisDuTemps = $query->get();

        // Classe sélectionnée
        $classeSelectionnee = $classeId ? Classe::find($classeId) : null;

        // Semaines disponibles POUR la classe sélectionnée
        $semainesDisponibles = EmploiDuTemps::query()
            ->when($classeId, function ($query) use ($classeId) {
                return $query->where('classe_id', $classeId);
            })
            ->select('semaine')
            ->distinct()
            ->orderBy('semaine')
            ->pluck('semaine')
            ->toArray();
        // dd($semainesDisponibles);

        // Niveaux et classes pour les selects
        $niveaux = Niveau::with('classes')->get();
        $classes = Classe::with('niveau')->orderBy('nom')->get();

        // Données pour tableau
        $jours = ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche'];
        $plagesHoraires = ['08:00', '09:00', '10:00', '11:00', '14:00', '15:00'];

        //         dd(
        //     EmploiDuTemps::select('semaine')->distinct()->pluck('semaine')->toArray()
        // );


        return view('admin.emplois.index', compact(
            'emploisDuTemps',
            'classes',
            'jours',
            'plagesHoraires',
            'niveaux',
            'classeSelectionnee',
            'semaine',
            'semainesDisponibles'
        ));
    }









    /**
     * Formulaire de création d’un créneau
     */
    public function create()
    {
        $matieres = Matiere::orderBy('nom')->get();
        $enseignants = Enseignant::orderBy('nom')->get();
        $classes = Classe::with('niveau')->orderBy('nom')->get();
        $niveaux = Niveau::all(); // ✅ à ajouter
        $jours = ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi'];
        $heures = ['08:00', '09:00', '10:00', '11:00', '13:00', '14:00', '15:00', '16:00'];


        return view('admin.emplois.create', compact('matieres', 'enseignants', 'classes', 'niveaux', 'jours', 'heures'));
    }

    /**
     * Enregistre un nouveau créneau
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'niveau_id' => 'required|exists:niveaux,id',
            'classe_id' => 'required|exists:classes,id',
            'matiere_id' => 'required|exists:matieres,id',
            'jour' => ['required', 'in:lundi,mardi,mercredi,jeudi,vendredi,samedi,dimanche'],
            'semaine' => 'required|integer|min:1|max:5',
            'heure_debut' => 'required|date_format:H:i',
            'heure_fin' => 'required|date_format:H:i|after:heure_debut',
        ]);

        $enseignant = Matiere::find($validated['matiere_id'])->enseignants()->first();

        if (!$enseignant) {
            return back()->with('error', 'Aucun enseignant trouvé pour cette matière.');
        }

        // Vérifie s’il y a un conflit à la même semaine
        $conflit = EmploiDuTemps::where('enseignant_id', $enseignant->id)
            ->where('jour', $validated['jour'])
            ->where('semaine', $validated['semaine']) // 🔥 TRÈS IMPORTANT
            ->where(function ($q) use ($validated) {
                $q->whereBetween('heure_debut', [$validated['heure_debut'], $validated['heure_fin']])
                    ->orWhereBetween('heure_fin', [$validated['heure_debut'], $validated['heure_fin']]);
            })
            ->exists();

        if ($conflit) {
            return back()->with('error', 'Créneau déjà occupé à ce jour, cette heure et semaine.');
        }

        // Enregistrement avec la semaine validée
        EmploiDuTemps::create([
            'niveau_id' => $validated['niveau_id'],
            'classe_id' => $validated['classe_id'],
            'matiere_id' => $validated['matiere_id'],
            'enseignant_id' => $enseignant->id,
            'jour' => $validated['jour'],
            'semaine' => $validated['semaine'], // 🔥 Pas $request->semaine !
            'heure_debut' => $validated['heure_debut'],
            'heure_fin' => $validated['heure_fin'],
        ]);

        return redirect()->route('admin.emplois-du-temps.index')->with('success', 'Créneau ajouté.');
    }








    /**
     * Formulaire d’édition d’un créneau
     */
    public function edit(EmploiDuTemps $emploi)
    {
        $matieres = Matiere::orderBy('nom')->get();
        $enseignants = Enseignant::orderBy('nom')->get();
        $classes = Classe::with('niveau')->orderBy('nom')->get();

        $jours = ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi'];
        $heures = ['08h00', '09h00', '10h00', '11h00', '13h00', '14h00', '15h00', '16h00'];

        return view('admin.emplois.edit', compact('emploi', 'matieres', 'enseignants', 'classes', 'jours', 'heures'));
    }

    /**
     * Mise à jour d’un créneau existant
     */
    public function update(Request $request, EmploiDuTemps $emploi)
    {
        $validated = $request->validate([
            'jour' => 'required|in:lundi,mardi,mercredi,jeudi,vendredi',
            'heure' => 'required|string',
            'matiere_id' => 'required|exists:matieres,id',
            'enseignant_id' => 'required|exists:enseignants,id',
            'classe_id' => 'required|exists:classes,id',
        ]);

        $exists = EmploiDuTemps::where('jour', $validated['jour'])
            ->where('heure', $validated['heure'])
            ->where('id', '!=', $emploi->id)
            ->whereHas('matiere', function ($query) use ($validated) {
                $query->where('classe_id', $validated['classe_id']);
            })
            ->first();

        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['heure' => 'Un créneau existe déjà pour cette classe à ce moment-là.']);
        }

        $emploi->update($validated);

        return redirect()->route('admin.emplois-du-temps.index')
            ->with('success', 'Créneau mis à jour avec succès.');
    }

    /**
     * Suppression d’un créneau
     */
    public function destroy($id)
    {
        $emploi = EmploiDuTemps::find($id);

        if (!$emploi) {
            return redirect()->route('admin.emplois-du-temps.index')
                ->with('error', "Créneau non trouvé.");
        }

        $emploi->delete();

        return redirect()->route('admin.emplois-du-temps.index')
            ->with('success', 'Créneau supprimé avec succès.');
    }





    /**
     * Glisser-déposer d’un créneau (reorder)
     */
    public function reorder(Request $request)
    {
        $validated = $request->validate([
            'creneau_id' => 'required|exists:emplois_du_temps,id',
            'toJour' => 'required|in:lundi,mardi,mercredi,jeudi,vendredi',
            'toHeure' => 'required|string',
        ]);

        $creneau = EmploiDuTemps::findOrFail($validated['creneau_id']);

        $exists = EmploiDuTemps::where('jour', $validated['toJour'])
            ->where('heure', $validated['toHeure'])
            ->where('id', '!=', $creneau->id)
            ->whereHas('matiere', function ($query) use ($creneau) {
                $query->where('classe_id', $creneau->matiere->classe_id);
            })
            ->first();

        if ($exists) {
            return response()->json(['success' => false, 'message' => 'Un créneau existe déjà à cet emplacement.'], 409);
        }

        $creneau->jour = $validated['toJour'];
        $creneau->heure = $validated['toHeure'];
        $creneau->save();

        return response()->json(['success' => true]);
    }

    public function getClassesByNiveau($niveauId)
    {
        $classes = Classe::where('niveau_id', $niveauId)->with('niveau')->get();

        $classes->transform(function ($classe) {
            $classe->niveau_nom = $classe->niveau->nom ?? '';
            return $classe;
        });

        return response()->json($classes);
    }

    // App\Http\Controllers\Admin\EmploiDuTempsController.php

    // App\Http\Controllers\Admin\EmploiDuTempsController.php

    public function getEnseignantParMatiere($matiereId)
    {
        $matiere = Matiere::with('enseignants')->findOrFail($matiereId);

        $enseignants = $matiere->enseignants;

        if ($enseignants->isEmpty()) {
            return response()->json(['message' => 'Aucun enseignant disponible'], 404);
        }

        return response()->json($enseignants);
    }

    public function exportPdf($semaine, Request $request)
    {
        $classeId = $request->query('classe_id');

        $query = EmploiDuTemps::with('matiere', 'enseignant', 'classe')
            ->where('semaine', $semaine);

        if ($classeId) {
            $query->where('classe_id', $classeId);
        }

        $emplois = $query->orderBy('jour')->orderBy('heure_debut')->get();

        return Pdf::loadView('admin.emplois.pdf', compact('emplois', 'semaine'))
            ->stream("emploi-du-temps-semaine-$semaine-classe-$classeId.pdf");
    }
}
