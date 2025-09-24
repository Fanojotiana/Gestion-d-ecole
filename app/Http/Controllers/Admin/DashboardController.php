<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Eleve;
use App\Models\Enseignant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $eleveCount = Eleve::count();
        $enseignantCount = Enseignant::count();

        return view('admin.dashboard', [
            'eleveCount' => $eleveCount,
            'enseignantCount' => $enseignantCount,
        ]);
    }
}
