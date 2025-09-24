@php
    abort_unless(auth()->user()?->role === 'admin', 403);
@endphp

@extends('admin.layoutsAdmin.dashboardLayoutsAdmin')

@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">

            {{-- Titre et fil d’Ariane --}}
            <div class="page-header mb-4">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">Modifier votre profil</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Profil</li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Notifications toastr --}}
            @if (session('success'))
                <script>
                    toastr.success("{{ session('success') }}");
                </script>
            @endif
            @if (session('error'))
                <script>
                    toastr.error("{{ session('error') }}");
                </script>
            @endif

            {{-- Erreurs validation --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Formulaire --}}
            <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row align-items-center gx-4">

                    {{-- Champs texte --}}
                    <div class="col-lg-8">
                        <div class="card p-3">
                            <div class="row gx-3 gy-2 align-items-center">
                                <div class="col-md-6">
                                    <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
                                    <input type="text" id="nom" name="nom" class="form-control"
                                        value="{{ old('nom', $admin->nom ?? '') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="prenom" class="form-label">Prénom <span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="prenom" name="prenom" class="form-control"
                                        value="{{ old('prenom', $admin->prenom ?? '') }}" required>
                                </div>
                                <div class="col-md-12">
                                    <label for="email" class="form-label">Email <span
                                            class="text-danger">*</span></label>
                                    <input type="email" id="email" name="email" class="form-control"
                                        value="{{ old('email', $admin->email ?? '') }}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Nouveau mot de passe (optionnel)</label>
                                    <input type="password" name="password" id="password" class="form-control"
                                        placeholder="Laisser vide pour ne pas changer">
                                </div>

                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">Confirmer le nouveau mot de
                                        passe</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                        class="form-control" placeholder="Confirmer le mot de passe">
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- Photo de profil --}}
                    <div class="col-lg-4">
                        <div class="card text-center p-3">
                            <h5 class="card-title mb-3">Photo de profil</h5>
                            <img id="avatar-preview"
                                src="{{ $admin->photo ? asset('storage/' . $admin->photo) : asset('storage/images/avatar.png') }}"
                                alt="Photo de profil" class="rounded-circle mx-auto d-block" width="160" height="160"
                                style="object-fit: cover; border: 3px solid #ccc;">
                            <div class="mt-3">
                                <input type="file" name="photo" id="photo" class="form-control" accept="image/*"
                                    onchange="previewPhoto(event)">
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Boutons --}}
                <div class="d-flex justify-content-center mt-4 gap-3 mb-4 ms-0">
                    <button type="submit" class="btn btn-primary">Mettre à jour</button>
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary">Annuler</a>
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
