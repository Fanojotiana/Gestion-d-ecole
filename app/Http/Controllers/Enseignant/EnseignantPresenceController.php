<?php

namespace App\Http\Controllers\Enseignant;

use App\Http\Controllers\Controller;
use App\Models\Presence;
use App\Models\Cours;
use App\Models\EmploiDuTemps;
use App\Models\Eleve;
use App\Models\Matiere;
use App\Models\Classe;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class EnseignantPresenceController extends Controller
{
    public function index($classeId)
    {
        $enseignantId = auth()->user()->id;
        $cours = Cours::findOrFail($id); // ou un autre moyen de récupérer un cours

        // Vérifie que la classe existe
        $classe = Classe::findOrFail($classeId);

        // Récupère tous les emplois du temps de cet enseignant dans cette classe
        $cours = EmploiDuTemps::with(['matiere', 'classe'])
            ->where('enseignant_id', $enseignantId)
            ->where('classe_id', $classeId)
            ->orderBy('jour')
            ->orderBy('heure_debut')
            ->get();

        return view('enseignant.presences.index', compact('cours', 'classe'));
    }


    public function show($coursId, $date = null)
    {
        // Utiliser la date d'aujourd'hui si aucune date n'est fournie
        $date = $date ?? Carbon::today()->format('Y-m-d');

        // Récupérer le cours avec ses relations
        $cours = Cours::with(['matiere', 'classe', 'eleves'])->findOrFail($coursId);

        // Vérifier que l'enseignant a bien accès à ce cours
        if ($cours->enseignant_id != Auth::id()) {
            abort(403, 'Accès non autorisé à ce cours');
        }

        // Récupérer les élèves de la classe du cours
        $eleves = $cours->classe->eleves ?? collect();

        // Si pas d'élèves via la classe, essayer via une relation directe cours-élèves
        if ($eleves->isEmpty() && method_exists($cours, 'eleves')) {
            $eleves = $cours->eleves;
        }

        // Récupérer les présences existantes pour cette date et ce cours
        $presences = Presence::where('cours_id', $coursId)
            ->where('date', $date)
            ->get()
            ->keyBy('eleve_id');

        return view('enseignant.presences.show', compact('cours', 'eleves', 'presences', 'date'));
    }

    public function store(Request $request)
    {
        // Validation des données
        $request->validate([
            'cours_id' => 'required|exists:cours,id',
            'date' => 'required|date',
            'presences' => 'required|array',
            'presences.*' => 'in:present,absent,retard'
        ], [
            'cours_id.required' => 'Le cours est requis',
            'cours_id.exists' => 'Le cours sélectionné n\'existe pas',
            'date.required' => 'La date est requise',
            'date.date' => 'Format de date invalide',
            'presences.required' => 'Aucune présence sélectionnée',
            'presences.*.in' => 'Statut de présence invalide'
        ]);

        // Vérifier que l'enseignant a accès au cours
        $cours = Cours::findOrFail($request->cours_id);
        if ($cours->enseignant_id != Auth::id()) {
            return redirect()->back()->with('error', 'Accès non autorisé à ce cours');
        }

        try {
            // Sauvegarder ou mettre à jour les présences
            foreach ($request->presences as $eleveId => $statut) {
                Presence::updateOrCreate(
                    [
                        'cours_id' => $request->cours_id,
                        'eleve_id' => $eleveId,
                        'date' => $request->date
                    ],
                    [
                        'statut' => $statut,
                        'remarque' => $request->remarques[$eleveId] ?? null,
                        'emploi_du_temps_id' => $this->getEmploiDuTempsId($request->cours_id, $request->date)
                    ]
                );
            }

            $totalEleves = count($request->presences);
            $presents = count(array_filter($request->presences, fn($s) => $s === 'present'));
            $absents = count(array_filter($request->presences, fn($s) => $s === 'absent'));
            $retards = count(array_filter($request->presences, fn($s) => $s === 'retard'));

            return redirect()->back()->with(
                'success',
                "Présences enregistrées avec succès ! ({$presents} présents, {$absents} absents, {$retards} retards sur {$totalEleves} élèves)"
            );
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de l\'enregistrement des présences : ' . $e->getMessage());
        }
    }

    public function rapport($coursId)
    {
        // Récupérer le cours avec ses relations
        $cours = Cours::with(['matiere', 'classe'])->findOrFail($coursId);

        // Vérifier l'accès
        if ($cours->enseignant_id != Auth::id()) {
            abort(403, 'Accès non autorisé à ce cours');
        }

        // Récupérer les élèves
        $eleves = $cours->classe->eleves ?? collect();
        if ($eleves->isEmpty() && method_exists($cours, 'eleves')) {
            $eleves = $cours->eleves;
        }

        // Calculer les statistiques de présence par élève
        $statistiques = [];
        foreach ($eleves as $eleve) {
            $totalPresences = Presence::where('cours_id', $coursId)
                ->where('eleve_id', $eleve->id)
                ->count();

            $presents = Presence::where('cours_id', $coursId)
                ->where('eleve_id', $eleve->id)
                ->where('statut', 'present')
                ->count();

            $absents = Presence::where('cours_id', $coursId)
                ->where('eleve_id', $eleve->id)
                ->where('statut', 'absent')
                ->count();

            $retards = Presence::where('cours_id', $coursId)
                ->where('eleve_id', $eleve->id)
                ->where('statut', 'retard')
                ->count();

            $tauxPresence = $totalPresences > 0 ? round(($presents / $totalPresences) * 100, 2) : 0;

            $statistiques[$eleve->id] = [
                'eleve' => $eleve,
                'total' => $totalPresences,
                'presents' => $presents,
                'absents' => $absents,
                'retards' => $retards,
                'taux_presence' => $tauxPresence
            ];
        }

        // Trier par taux de présence décroissant
        uasort($statistiques, function ($a, $b) {
            return $b['taux_presence'] <=> $a['taux_presence'];
        });

        return view('enseignant.presences.rapport', compact('cours', 'statistiques'));
    }

    /**
     * Obtenir l'ID de l'emploi du temps pour un cours et une date donnés
     */
    private function getEmploiDuTempsId($coursId, $date)
    {
        try {
            // Convertir la date en jour de la semaine (1 = Lundi, 7 = Dimanche)
            $jourSemaine = Carbon::parse($date)->dayOfWeek;
            if ($jourSemaine == 0) $jourSemaine = 7; // Dimanche = 7

            // Mapper les jours en français si nécessaire
            $joursMapping = [
                1 => 'lundi',
                2 => 'mardi',
                3 => 'mercredi',
                4 => 'jeudi',
                5 => 'vendredi',
                6 => 'samedi',
                7 => 'dimanche'
            ];

            $jourNom = $joursMapping[$jourSemaine];

            // Chercher l'emploi du temps correspondant
            $emploiDuTemps = EmploiDuTemps::where('cours_id', $coursId)
                ->where('jour', $jourNom)
                ->first();

            return $emploiDuTemps ? $emploiDuTemps->id : null;
        } catch (\Exception $e) {
            // En cas d'erreur, retourner null
            return null;
        }
    }

    /**
     * API pour obtenir les élèves d'un cours (utile pour AJAX)
     */
    public function getEleves($coursId)
    {
        $cours = Cours::with('classe.eleves')->findOrFail($coursId);

        if ($cours->enseignant_id != Auth::id()) {
            return response()->json(['error' => 'Accès non autorisé'], 403);
        }

        $eleves = $cours->classe->eleves ?? collect();

        return response()->json([
            'success' => true,
            'eleves' => $eleves->map(function ($eleve) {
                return [
                    'id' => $eleve->id,
                    'nom' => $eleve->nom,
                    'prenom' => $eleve->prenom,
                    'numero_etudiant' => $eleve->numero_etudiant ?? null
                ];
            })
        ]);
    }

    /**
     * Statistiques rapides pour le dashboard
     */
    public function statistiquesRapides()
    {
        $enseignantId = Auth::id();
        $aujourdhui = Carbon::today();

        // Nombre total de cours de l'enseignant
        $totalCours = Cours::where('enseignant_id', $enseignantId)->count();

        // Présences prises aujourd'hui
        $presencesAujourdhui = Presence::whereHas('cours', function ($query) use ($enseignantId) {
            $query->where('enseignant_id', $enseignantId);
        })->where('date', $aujourdhui)->count();

        // Taux de présence moyen sur les 30 derniers jours
        $debutMois = Carbon::now()->subDays(30);
        $presencesMois = Presence::whereHas('cours', function ($query) use ($enseignantId) {
            $query->where('enseignant_id', $enseignantId);
        })->where('date', '>=', $debutMois)
            ->selectRaw('statut, COUNT(*) as count')
            ->groupBy('statut')
            ->pluck('count', 'statut');

        $totalMois = $presencesMois->sum();
        $tauxPresenceMois = $totalMois > 0 ? round(($presencesMois->get('present', 0) / $totalMois) * 100, 1) : 0;

        return response()->json([
            'total_cours' => $totalCours,
            'presences_aujourdhui' => $presencesAujourdhui,
            'taux_presence_mois' => $tauxPresenceMois,
            'absences_mois' => $presencesMois->get('absent', 0)
        ]);
    }
}
