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
                        <h3 class="page-title">Ajouter un élève</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Nouvel élève</li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Messages d'erreur --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.eleves.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row align-items-center gx-4">

                    {{-- Partie formulaire champs (en paysage) --}}
                    <div class="col-lg-8">
                        <div class="card p-3">
                            <div class="row gx-3 gy-2 align-items-center">
                                <div class="col-md-6">
                                    <label for="matricule" class="form-label">Matricule <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="matricule" id="matricule" class="form-control" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
                                    <input type="text" name="nom" id="nom" class="form-control" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="prenom" class="form-label">Prénom <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="prenom" id="prenom" class="form-control" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="genre" class="form-label">Genre <span
                                            class="text-danger">*</span></label>
                                    <select name="genre" id="genre" class="form-control" required>
                                        <option value="">-- Choisir --</option>
                                        <option value="M">Masculin</option>
                                        <option value="F">Féminin</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="date_naissance" class="form-label">Date de naissance</label>
                                    <input type="date" name="date_naissance" id="date_naissance" class="form-control">
                                </div>

                                <div class="col-md-6">
                                    <label for="urgence_contact" class="form-label">Personne à contacter en cas
                                        d'urgence</label>
                                    <input type="text" name="urgence_contact" id="urgence_contact" class="form-control"
                                        placeholder="Nom et téléphone">
                                </div>

                                <div class="col-md-12">
                                    <label for="adresse" class="form-label">Adresse</label>
                                    <textarea name="adresse" id="adresse" class="form-control" rows="2"></textarea>
                                </div>

                                <div class="col-md-6">
                                    <label for="niveau_id" class="form-label">Niveau <span
                                            class="text-danger">*</span></label>
                                    <select name="niveau_id" id="niveau_id" class="form-control" required>
                                        <option value="">-- Choisir un niveau --</option>
                                        @foreach ($niveaux as $niveau)
                                            <option value="{{ $niveau->id }}">{{ $niveau->nom }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="classe_id" class="form-label">Classe <span
                                            class="text-danger">*</span></label>
                                    <select name="classe_id" id="classe_id" class="form-control" required>
                                        <option value="">-- Sélectionnez une classe --</option>
                                        @foreach ($classes as $classe)
                                            <option value="{{ $classe->id }}">
                                                {{ $classe->nom }} ({{ $classe->niveau->nom ?? 'Niveau inconnu' }})
                                            </option>
                                        @endforeach
                                    </select>
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
                                <input type="file" name="photo" class="form-control"
                                    onchange="previewPhoto(event)">
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Boutons --}}
                <div class="d-flex justify-content-center mt-4 gap-3 mb-4">
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                    <a href="{{ route('admin.eleves.index') }}" class="btn btn-secondary">Annuler</a>
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

        // Gestion dynamique des classes selon niveau sélectionné
        document.getElementById('niveau_id').addEventListener('change', function() {
            const niveauId = this.value;
            const classeSelect = document.getElementById('classe_id');
            const classes = @json($classes);

            classeSelect.innerHTML = '<option value="">-- Sélectionnez une classe --</option>';

            if (niveauId) {
                const classesFiltrees = classes.filter(c => c.niveau_id == niveauId);
                classesFiltrees.forEach(classe => {
                    const option = document.createElement('option');
                    option.value = classe.id;
                    option.textContent =
                        `${classe.nom} (${classe.niveau ? classe.niveau.nom : 'Niveau inconnu'})`;
                    classeSelect.appendChild(option);
                });
            }
        });
    </script>
@endsection
