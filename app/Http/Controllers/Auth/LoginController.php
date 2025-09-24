<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function loginForm()
    {
        return view('pages.auth.login');
    }
    public function login(Request $request)
    {
        // Valider les champs du formulaire
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Tenter de connecter l'utilisateur
        if (Auth::attempt($credentials)) {
            // Regénérer la session (sécurité contre fixation de session)
            $request->session()->regenerate();

            // Récupérer l'utilisateur connecté
            $user = Auth::user();

            // Rediriger selon le rôle
            switch ($user->role) {
                case 'admin':
                    return to_route('admin.dashboard');
                case 'enseignant':
                    return to_route('dashboard.enseignant');
                case 'responsable':
                    return to_route('responsable.dashboard');
                    // ajoute d'autres rôles si besoin
                default:
                    return to_route('dashboard'); // route générique par défaut
            }
        }

        // En cas d'échec de connexion
        return back()->with(
            'error',
            'Les identifiants sont incorrects.'
        );
    }
}
