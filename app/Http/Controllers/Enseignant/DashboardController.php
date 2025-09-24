<?php

namespace App\Http\Controllers\Enseignant;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function dashboard()
    {
        // $user = Auth::user();

        return view('enseignant.dashboard');
    }
}
