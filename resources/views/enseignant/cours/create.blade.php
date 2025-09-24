@extends('responsable.layoutsResponsable.dashboardLayoutsResponsable') {{-- ou ton layout principal --}}

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h3 class="mb-0">Créer un nouveau cours</h3>
        </div>

        <div class="card-body">
            {{-- Affichage des erreurs de validation --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('cours.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="matiere_id" class="form-label">Matière <span class="text-danger">*</span></label>
                    <select name="matiere_id" id="matiere_id" class="form-select" required>
                        <option value="">-- Choisir une matière --</option>
                        @foreach ($matieres as $matiere)
                            <option value="{{ $matiere->id }}" {{ old('matiere_id') == $matiere->id ? 'selected' : '' }}>
                                {{ $matiere->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="classe_id" class="form-label">Classe <span class="text-danger">*</span></label>
                    <select name="classe_id" id="classe_id" class="form-select" required>
                        <option value="">-- Choisir une classe --</option>
                        @foreach ($classes as $classe)
                            <option value="{{ $classe->id }}" {{ old('classe_id') == $classe->id ? 'selected' : '' }}>
                                {{ $classe->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="enseignant_id" class="form-label">Enseignant <span class="text-danger">*</span></label>
                    <select name="enseignant_id" id="enseignant_id" class="form-select" required>
                        <option value="">-- Choisir un enseignant --</option>
                        @foreach ($enseignants as $enseignant)
                            <option value="{{ $enseignant->id }}" {{ old('enseignant_id') == $enseignant->id ? 'selected' : '' }}>
                                {{ $enseignant->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="nom" class="form-label">Nom du cours (optionnel)</label>
                    <input type="text" id="nom" name="nom" class="form-control" value="{{ old('nom') }}" placeholder="Nom du cours">
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description (optionnel)</label>
                    <textarea id="description" name="description" class="form-control" rows="4" placeholder="Description du cours">{{ old('description') }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> Créer le cours
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
