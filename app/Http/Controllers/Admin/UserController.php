<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('admin.utilisateurs.index', compact('users'));
    }

    public function create()
    {
        return view('admin.utilisateurs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'role' => 'required|string',
            'password' => 'required|min:6|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => bcrypt($request->password),
        ]);

        return redirect()->route('admin.utilisateurs.index')->with('success', 'Utilisateur créé avec succès.');
    }

    public function edit(User $utilisateur)
    {
        return view('admin.utilisateurs.edit', compact('utilisateur'));
    }

    public function update(Request $request, User $utilisateur)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $utilisateur->id,
            'role' => 'required|string',
            'password' => 'nullable|min:6|confirmed',
        ]);

        $data = $request->only('name', 'email', 'role');

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $utilisateur->update($data);

        return redirect()->route('admin.utilisateurs.index')->with('success', 'Utilisateur mis à jour.');
    }

    public function destroy(User $utilisateur)
    {
        $utilisateur->delete();
        return redirect()->route('admin.utilisateurs.index')->with('success', 'Utilisateur supprimé.');
    }
}
