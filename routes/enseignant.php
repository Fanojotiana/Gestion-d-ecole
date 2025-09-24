<?php

use App\Http\Controllers\Enseignant\CoursController;
use App\Http\Controllers\Enseignant\DashboardController;
use App\Http\Controllers\Enseignant\EmploiDuTempsController;
// use App\Http\Controllers\Enseignant\PresenceController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth', 'enseignant'])->group(function () {

    // Tableau de bord de l'enseignant
    Route::get('/dashboard-enseignant', [DashboardController::class, 'dashboard'])->name('dashboard.enseignant');

    // Route::get('/presences-enseignant', [PresenceController::class, 'indexEnseignant'])->name('presences.enseignant.index');
    // Route::post('/presences-enseignant', [PresenceController::class, 'store'])->name('presences.enseignant.store');
    // Route::resource('cours', CoursController::class);

    //Emploi du temps
    Route::get('/emplois-du-temps', [EmploiDuTempsController::class, 'index'])->name('enseignant.emplois.index');
    // Présences
    // Route::get('enseignant/presences', [PresenceController::class, 'index'])->name('enseignant.presences.index');

    // Route::get('/enseignant/presences/{cours}', [PresenceController::class, 'showElevesClasse'])->name('enseignant.presences.form');
    // Route::post('/enseignant/presences/{emploiDuTemps}', [PresenceController::class, 'store'])->name('enseignant.presences.store');

    // Route::get('/presences/cours/{cours}', [PresenceController::class, 'indexPresence'])->name('enseignant.presences.parcours');
    // Route::get('/presences/{presence}/edit', [PresenceController::class, 'edit'])->name('enseignant.presences.edit');
    // Route::delete('/presences/{presence}', [PresenceController::class, 'destroy'])->name('enseignant.presences.destroy');
    // Route::get('/enseignant/presences/{classe}/{matiere}', [PresenceController::class, 'liste'])->name('enseignant.presences.liste');

    // Routes pour l'espace enseignant

        Route::get('/presences/cours/{id}', [App\Http\Controllers\Enseignant\EnseignantPresenceController::class, 'index'])->name('enseignant.presences.index');
        Route::get('/presences/{cours}/{date?}', [App\Http\Controllers\Enseignant\EnseignantPresenceController::class, 'show'])->name('enseignant.presences.show');
        Route::post('/presences', [App\Http\Controllers\Enseignant\EnseignantPresenceController::class, 'store'])->name('presences.store');
        Route::get('/presences/rapport/{cours}', [App\Http\Controllers\Enseignant\EnseignantPresenceController::class, 'rapport'])->name('enseignant.presences.rapport');

          Route::get('/api/cours/{cours}/eleves', [App\Http\Controllers\Enseignant\EnseignantPresenceController::class, 'getEleves'])->name('enseignant.presences.eleves');
    Route::get('/api/statistiques-rapides', [App\Http\Controllers\Enseignant\EnseignantPresenceController::class, 'statistiquesRapides'])->name('enseignant.presences.stats');

});
