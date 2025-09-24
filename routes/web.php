<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EnseignantController;
use App\Http\Controllers\auth\AuthController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\auth\RegisterController;
use App\Http\Controllers\Enseignant\PresenceController;
use App\Http\Controllers\menu\MenuController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('accueil');
});
Route::get('/login', [LoginController::class, 'loginForm'])->name('login');
// Route::get('/menu', [MenuController::class, 'menu'])->name('school.menu');

/*AUTHENTIFICATION ET ACCOUNT*/
Route::post('/login', [LoginController::class, 'login'])->name('login.store');
// Route::post('/register', [RegisterController::class, 'register'])->name('register.store');
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');



/*DASHBOARD*/
Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard')->Middleware('auth');

/*ESPACE ENSEIGNANT*/
require __DIR__ . '/enseignant.php';


/*ESPACE RESPONSABLE*/
require __DIR__ . '/responsable.php';

use App\Models\Eleve;

// Route::get('/test-eleves', function () {
//     $eleves = Eleve::with('classe.niveau')->limit(10)->get();

//     foreach ($eleves as $eleve) {
//         echo "Nom: {$eleve->nom} <br>";
//         echo "Classe: " . ($eleve->classe?->nom ?? 'Classe absente') . "<br>";
//         echo "Niveau: " . ($eleve->classe?->niveau?->nom ?? 'Niveau absent') . "<br><br>";
//     }
// });
