@php
    abort_unless(auth()->user()?->role === 'admin', 403);
@endphp

@extends('admin.layoutsAdmin.dashboardLayoutsAdmin')

<x-swal />
@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">

            {{-- Titre --}}
            <div class="page-header mb-4">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">Ajouter un responsable</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Nouveau responsable</li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Formulaire --}}
            <form action="{{ route('admin.responsables.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row align-items-center gx-4">

                    {{-- Partie formulaire champs (à gauche) --}}
                    <div class="col-lg-8">
                        <div class="card p-3">
                            <div class="row gx-3 gy-2 align-items-center">
                                <div class="col-md-6">
                                    <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
                                    <input type="text" id="nom" name="nom" class="form-control" required value="{{ old('nom') }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="prenom" class="form-label">Prénom <span class="text-danger">*</span></label>
                                    <input type="text" id="prenom" name="prenom" class="form-control" required value="{{ old('prenom') }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="date_naissance" class="form-label">Date de naissance <span class="text-danger">*</span></label>
                                    <input type="date" id="date_naissance" name="date_naissance" class="form-control" required value="{{ old('date_naissance') }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="sexe" class="form-label">Sexe <span class="text-danger">*</span></label>
                                    <select id="sexe" name="sexe" class="form-select" required>
                                        <option value="" disabled selected>Choisir</option>
                                        <option value="Masculin" {{ old('sexe') == 'Masculin' ? 'selected' : '' }}>Masculin</option>
                                        <option value="Féminin" {{ old('sexe') == 'Féminin' ? 'selected' : '' }}>Féminin</option>
                                        <option value="Autre" {{ old('sexe') == 'Autre' ? 'selected' : '' }}>Autre</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="adresse" class="form-label">Adresse complète</label>
                                    <textarea id="adresse" name="adresse" class="form-control" rows="2">{{ old('adresse') }}</textarea>
                                </div>
                                <div class="col-md-6">
                                    <label for="telephone" class="form-label">Téléphone</label>
                                    <input type="text" id="telephone" name="telephone" class="form-control" value="{{ old('telephone') }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email professionnel <span class="text-danger">*</span></label>
                                    <input type="email" id="email" name="email" class="form-control" required value="{{ old('email') }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="responsable_type" class="form-label">Type de responsable <span class="text-danger">*</span></label>
                                    <select id="responsable_type" name="responsable_type" class="form-select" required>
                                        <option value="" disabled selected>Choisir</option>
                                        <option value="type1" {{ old('responsable_type') == 'type1' ? 'selected' : '' }}>Type 1</option>
                                        <option value="type2" {{ old('responsable_type') == 'type2' ? 'selected' : '' }}>Type 2</option>
                                        <option value="type3" {{ old('responsable_type') == 'type3' ? 'selected' : '' }}>Type 3</option>
                                        {{-- adapte les types selon ton projet --}}
                                    </select>
                                </div>
                                <div class="mb-3 mt-3">
                                    <label for="password" class="form-label">Mot de passe <span class="text-danger">*</span></label>
                                    <input type="password" id="password" name="password" class="form-control" style="width: 50%;" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Partie photo de profil (à droite) --}}
                    <div class="col-lg-4">
                        <div class="card text-center p-3">
                            <h5 class="card-title mb-3">Photo de profil</h5>
                            <img id="avatar-preview" src="{{ asset('storage/images/avatar.png') }}" alt="Photo de profil"
                                class="rounded-circle mx-auto d-block" width="160" height="160"
                                style="object-fit: cover; border: 3px solid #ccc;">
                            <div class="mt-3">
                                <input type="file" name="photo" class="form-control" onchange="previewPhoto(event)">
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Boutons --}}
                <div class="d-flex justify-content-center mt-4 gap-3 mb-4 ms-0">
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                    <a href="{{ route('admin.responsables.index') }}" class="btn btn-secondary">Annuler</a>
                </div>

            </form>

        </div>
    </div>

    {{-- Script de prévisualisation photo --}}
    <script>
        function previewPhoto(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const output = document.getElementById('avatar-preview');
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
@endsection
