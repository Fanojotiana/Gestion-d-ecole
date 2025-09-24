<?php

namespace App\Http\Controllers\Enseignant;

use App\Http\Controllers\Controller;
use App\Models\EmploiDuTemps;
use Illuminate\Http\Request;

class EmploiDuTempsController extends Controller
{


    public function index(Request $request)
    {
        $enseignant = auth()->user()->enseignant;

        if (!$enseignant) {
            abort(403, 'Aucun enseignant lié à cet utilisateur.');
        }

        $classeId = $request->query('classe_id');
        $semaine = $request->query('semaine');

        $semaineFilter = is_numeric($semaine) ? intval($semaine) : null;

        $query = EmploiDuTemps::with(['classe', 'matiere'])
            ->where('enseignant_id', $enseignant->id);

        if ($classeId) {
            $query->where('classe_id', $classeId);
        }

        if ($semaineFilter) {
            $query->where('semaine', $semaineFilter);
        }

        $emploisDuTemps = $query->orderBy('jour')->orderBy('heure_debut')->get();

        $classeIds = EmploiDuTemps::where('enseignant_id', $enseignant->id)
            ->whereNotNull('classe_id')
            ->distinct()
            ->pluck('classe_id');

        $classes = \App\Models\Classe::whereIn('id', $classeIds)
            ->orderBy('nom')
            ->get();

        $semainesQuery = EmploiDuTemps::where('enseignant_id', $enseignant->id);
        if ($classeId) {
            $semainesQuery->where('classe_id', $classeId);
        }
        $semainesDisponibles = $semainesQuery->select('semaine')
            ->distinct()
            ->orderBy('semaine')
            ->pluck('semaine');

        $jours = ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi'];
        $plagesHoraires = ['08:00', '09:00', '10:00', '11:00', '14:00', '15:00'];

        return view('enseignant.emplois.index', compact(
            'emploisDuTemps',
            'classes',
            'semainesDisponibles',
            'jours',
            'plagesHoraires',
            'classeId',
            'semaine'
        ));
    }






    // public function emploiDuTemps()
    // {
    //     $enseignantId = auth()->user()->id;

    //     $emploisDuTemps = EmploiDuTemps::with(['matiere', 'classe'])
    //         ->where('enseignant_id', $enseignantId)
    //         ->orderBy('jour')
    //         ->orderBy('heure_debut')
    //         ->get();

    //     return view('enseignant.emplois.index', compact('emploisDuTemps'));
    // }
}
