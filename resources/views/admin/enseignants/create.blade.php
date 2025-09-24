@php
    abort_unless(auth()->user()?->role === 'admin', 403);
@endphp

@extends('admin.layoutsAdmin.dashboardLayoutsAdmin')

@section('title', 'Ajouter un enseignant')

<x-swal />

@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">

            {{-- Titre et fil d’Ariane --}}
            <div class="page-header mb-4">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">Ajouter un enseignant</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.enseignants.index') }}">Enseignants</a></li>
                            <li class="breadcrumb-item active">Ajouter</li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Messages d'erreur --}}
            {{-- @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif --}}

            {{-- Message succès --}}
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form action="{{ route('admin.enseignants.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row gx-4">

                    {{-- Formulaire champs (plus large) --}}
                    <div class="col-lg-8">
                        <div class="card p-3">
                            <div class="row gx-3 gy-2 align-items-center">
                                <div class="col-md-6">
                                    <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
                                    <input type="text" name="nom" id="nom" class="form-control"
                                        value="{{ old('nom') }}" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="prenom" class="form-label">Prénom <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="prenom" id="prenom" class="form-control"
                                        value="{{ old('prenom') }}" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="matricule" class="form-label">Matricule <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="matricule" id="matricule" class="form-control"
                                        value="{{ old('matricule') }}" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="matieres" class="form-label">Matières à enseigner <span
                                            class="text-danger">*</span></label>
                                    <select name="matieres[]" id="matieres" class="form-select" multiple required>
                                        <option value="">-- Choisir les matières --</option>
                                        @foreach ($matieres as $matiere)
                                            <option value="{{ $matiere->id }}"
                                                {{ in_array($matiere->id, old('matieres', [])) ? 'selected' : '' }}>
                                                {{ $matiere->nom }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="col-md-6">
                                    <label for="grade" class="form-label">Grade</label>
                                    <input type="text" name="grade" id="grade" class="form-control"
                                        value="{{ old('grade') }}">
                                </div>

                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email <span
                                            class="text-danger">*</span></label>
                                    <input type="email" name="email" id="email" class="form-control"
                                        value="{{ old('email') }}" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="telephone" class="form-label">Téléphone</label>
                                    <input type="text" name="telephone" id="telephone" class="form-control"
                                        value="{{ old('telephone') }}">
                                </div>

                                <div class="col-md-12">
                                    <label for="adresse" class="form-label">Adresse</label>
                                    <textarea name="adresse" id="adresse" rows="2" class="form-control">{{ old('adresse') }}</textarea>
                                </div>

                                <div class="col-md-6">
                                    <label for="password" class="form-label">Mot de passe <span
                                            class="text-danger">*</span></label>
                                    <input type="password" name="password" id="password" class="form-control" required>
                                </div>

                                {{-- Optionnel: sélection classes si tu veux --}}
                                {{-- @if (isset($classes) && $classes->count())
                                    <div class="col-md-6">
                                        <label for="classe_id" class="form-label">Classe</label>
                                        <select name="classe_id" id="classe_id" class="form-select">
                                            <option value="">-- Choisir une classe --</option>
                                            @foreach ($classes as $classe)
                                                <option value="{{ $classe->id }}"
                                                    {{ old('classe_id') == $classe->id ? 'selected' : '' }}>
                                                    {{ $classe->nom }} - {{ $classe->niveau->nom ?? '' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif --}}
                            </div>
                        </div>
                    </div>

                    {{-- Photo de profil (à droite) --}}
                    <div class="col-lg-4">
                        <div class="card text-center p-3">
                            <h5 class="card-title mb-3">Photo de profil</h5>
                            <img id="avatar-preview" src="{{ asset('storage/images/avatar.png') }}" alt="Photo de profil"
                                class="rounded-circle mx-auto d-block" width="160" height="160"
                                style="object-fit: cover; border: 3px solid #ccc;">
                            <div class="mt-3">
                                <input type="file" name="photo" class="form-control"
                                    onchange="previewPhoto(event)">
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Boutons --}}
                <div class="d-flex justify-content-center mt-4 gap-3 mb-4">
                    <button type="submit" class="btn btn-primary">Ajouter</button>
                    <a href="{{ route('admin.enseignants.index') }}" class="btn btn-secondary">Annuler</a>
                </div>
            </form>

        </div>
    </div>

    {{-- Script de prévisualisation photo --}}
    <script>
        function previewPhoto(event) {
            const reader = new FileReader();
            reader.onload = function() {
                document.getElementById('avatar-preview').src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
@endsection
