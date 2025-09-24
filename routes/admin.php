<?php

// use App\Http\Controllers\Admin\EmploiDuTempsController;
use App\Http\Controllers\Admin\EmploiDuTempsController;
use App\Http\Controllers\Admin\HomeContentController;
use App\Http\Controllers\Admin\NoteController;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ClasseController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EleveController;
use App\Http\Controllers\Admin\EnseignantController;
use App\Http\Controllers\Admin\MatiereController;
use App\Http\Controllers\Admin\NiveauController;
use App\Http\Controllers\Admin\PresenceController;
use App\Http\Controllers\Admin\ResponsableController;
use App\Http\Controllers\Admin\UserController;


Route::middleware(['auth', 'admin'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

    // Profil
    Route::get('/profile/edit', [AdminController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile/update', [AdminController::class, 'updateProfile'])->name('profile.update');

    // Enseignants
    Route::resource('enseignants', EnseignantController::class);

    // Responsables
    Route::resource('responsables', ResponsableController::class);

    // Niveaux
    Route::resource('niveaux', NiveauController::class);

    // Élèves
    Route::resource('eleves', EleveController::class)->parameters([
        'eleves' => 'eleve'
    ])->except(['show']);

    // Classes
    Route::resource('classes', ClasseController::class);

    // Matières
    Route::resource('matieres', MatiereController::class)->except(['create', 'show']);

    Route::resource('emplois-du-temps', EmploiDuTempsController::class)->except(['show']);

    Route::post('/emplois/reorder', [EmploiDuTempsController::class, 'reorder'])->name('emplois.reorder');

    Route::get('/niveaux/{niveau}/classes', [EmploiDuTempsController::class, 'getClassesByNiveau'])
        ->name('admin.niveaux.classes');
    // routes/web.php
    Route::get('/enseignant-par-matiere/{matiereId}', [EmploiDuTempsController::class, 'getEnseignantParMatiere']);

    Route::get('/emplois-du-temps/pdf/{semaine}', [EmploiDuTempsController::class, 'exportPdf'])
        ->name('emplois-du-temps.pdf');


    // NOTE
    Route::resource('notes', NoteController::class);
    Route::get('/classes/{classe}/eleves', [NoteController::class, 'getEleves'])->name('admin.classes.eleves');
    Route::put('/notes/update-multiple/{eleve}', [NoteController::class, 'updateMultiple'])->name('admin.notes.updateMultiple');
    // {{--RANG--}}
    Route::get('/eleves/rang', [NoteController::class, 'rangParClasse'])->name('rang.eleves');
    Route::resource('notes', NoteController::class)->except(['show']);

    // Dans web.php ou routes admin
    Route::get('/notes/bulletin/{eleve}', [NoteController::class, 'exportBulletinPdf'])
        ->name('notes.bulletin.pdf');

    // --PRESENCE--
    // Route::get('/presences', [PresenceController::class, 'index'])->name('presences.index');
    // Route::post('/presences', [PresenceController::class, 'store'])->name('presences.store');
    Route::get('/presences', [PresenceController::class, 'index'])->name('presences.index');

    // PAGE D'ACCUEIL
    Route::get('/admin/home-content', [HomeContentController::class, 'edit'])->name('home-content.edit');
    Route::post('/admin/home-content', [HomeContentController::class, 'update'])->name('home-content.update');
});
