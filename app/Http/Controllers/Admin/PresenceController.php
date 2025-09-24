<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Presence;
use App\Models\Cours;
use App\Models\Eleve;
use Illuminate\Http\Request;

class PresenceController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->input('date', now()->toDateString());
        $coursId = $request->input('cours_id');

        $coursList = Cours::with('classe')->get();

        $eleves = [];
        if ($coursId) {
            $cours = Cours::find($coursId);
            $eleves = $cours->classe->eleves ?? [];
        }

        return view('admin.presence.index', compact('coursList', 'eleves', 'coursId', 'date'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'cours_id' => 'required|exists:cours,id',
            'presences' => 'required|array',
        ]);

        foreach ($request->presences as $eleveId => $presenceData) {
            Presence::updateOrCreate(
                [
                    'cours_id' => $request->cours_id,
                    'eleve_id' => $eleveId,
                    'date' => $request->date,
                ],
                [
                    'statut' => $presenceData['statut'],
                    'remarque' => $presenceData['remarque'] ?? null,
                ]
            );
        }

        return back()->with('success', 'Présences enregistrées avec succès.');
    }
}
