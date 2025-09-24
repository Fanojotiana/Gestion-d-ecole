<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;



class AdminController extends Controller
{
    public function editProfile()
    {
        $admin = auth()->user();
        return view('admin.profile.edit', compact('admin'));
    }

    public function updateProfile(Request $request)
    {
        /** @var \App\Models\User $admin */
        $admin = auth()->user();

        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $admin->id,
            'photo' => 'nullable|image|max:2048',
            'password' => 'nullable|string|min:8|confirmed',  // 'confirmed' vérifie password_confirmation
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.profile.edit')
                ->withErrors($validator)
                ->withInput();
        }

        $admin->nom = $request->nom;
        $admin->prenom = $request->prenom;
        $admin->email = $request->email;

        if ($request->hasFile('photo')) {
            if ($admin->photo && Storage::disk('public')->exists($admin->photo)) {
                Storage::disk('public')->delete($admin->photo);
            }
            $path = $request->file('photo')->store('admin_photos', 'public');
            $admin->photo = $path;
        }

        // Mise à jour du mot de passe uniquement s'il est rempli
        if ($request->filled('password')) {
            $admin->password = bcrypt($request->password);
        }

        $admin->save();

        return redirect()->route('admin.profile.edit')->with('success', 'Profil mis à jour avec succès.');
    }
}
