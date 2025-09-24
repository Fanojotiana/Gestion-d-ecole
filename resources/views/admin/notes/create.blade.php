@extends('admin.layoutsAdmin.dashboardLayoutsAdmin')

@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">

            <div class="card shadow border-0">
                <div class="card-header bg-white">
                    <h5 class="fw-bold mb-0">
                        <i class="fas fa-plus-circle me-2 text-success"></i> Ajouter une note
                    </h5>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('admin.notes.store') }}">
                        @csrf

                        {{-- Sélection de la classe --}}
                        <div class="mb-3">
                            <label for="classe_id" class="form-label">Classe</label>
                            <select name="classe_id" id="classe_id" class="form-select" onchange="getEleves(this.value)"
                                required>
                                <option value="">-- Sélectionner une classe --</option>
                                @foreach ($classes as $classe)
                                    <option value="{{ $classe->id }}"
                                        {{ old('classe_id') == $classe->id ? 'selected' : '' }}>
                                        {{ $classe->nom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('classe_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Élèves dépendant de la classe --}}
                        <div class="mb-3">
                            <label for="eleve_id" class="form-label">Élève</label>
                            <select name="eleve_id" id="eleve_id" class="form-select" required>
                                <option value="">-- Sélectionner un élève --</option>
                                {{-- Rempli dynamiquement par JS --}}
                            </select>
                            @error('eleve_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Matière --}}
                        <div class="mb-3">
                            <label for="matiere_id" class="form-label">Matière</label>
                            <select name="matiere_id" id="matiere_id" class="form-select" required>
                                <option value="">-- Sélectionner une matière --</option>
                                @foreach ($matieres as $matiere)
                                    <option value="{{ $matiere->id }}"
                                        {{ old('matiere_id') == $matiere->id ? 'selected' : '' }}>
                                        {{ $matiere->nom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('matiere_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Note --}}
                        <div class="mb-3">
                            <label for="note" class="form-label">Note (/20)</label>
                            <input type="number" step="0.01" max="20" name="note" id="note"
                                class="form-control" value="{{ old('note') }}" required>
                            @error('note')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Coefficient --}}
                        <div class="mb-3">
                            <label for="coefficient" class="form-label">Coefficient</label>
                            <input type="number" step="0.1" name="coefficient" id="coefficient" class="form-control"
                                value="{{ old('coefficient', 1) }}" required>
                            @error('coefficient')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Période --}}
                        <div class="mb-3">
                            <label for="periode" class="form-label">Période</label>
                            <select name="periode" id="periode" class="form-select" required>
                                <option value="">-- Choisir --</option>
                                <option value="Trimestre 1" {{ old('periode') == 'Trimestre 1' ? 'selected' : '' }}>
                                    Trimestre 1</option>
                                <option value="Trimestre 2" {{ old('periode') == 'Trimestre 2' ? 'selected' : '' }}>
                                    Trimestre 2</option>
                                <option value="Trimestre 3" {{ old('periode') == 'Trimestre 3' ? 'selected' : '' }}>
                                    Trimestre 3</option>
                            </select>
                            @error('periode')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Commentaire --}}
                        <div class="mb-3">
                            <label for="commentaire" class="form-label">Commentaire (facultatif)</label>
                            <textarea name="commentaire" id="commentaire" class="form-control">{{ old('commentaire') }}</textarea>
                        </div>

                        {{-- Boutons --}}
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Enregistrer
                            </button>
                            <a href="{{ route('admin.notes.index') }}" class="btn btn-secondary">Annuler</a>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>

    {{-- Script pour charger dynamiquement les élèves selon la classe --}}
    <script>
        function getEleves(classeId) {
            const eleveSelect = document.getElementById('eleve_id');
            eleveSelect.innerHTML = '<option value="">Chargement...</option>';

            if (!classeId) {
                eleveSelect.innerHTML = '<option value="">-- Sélectionner un élève --</option>';
                return;
            }

            fetch(`/admin/classes/${classeId}/eleves`)
                .then(response => {
                    if (!response.ok) throw new Error('Erreur réseau');
                    return response.json();
                })
                .then(data => {
                    eleveSelect.innerHTML = '<option value="">-- Sélectionner un élève --</option>';
                    data.forEach(eleve => {
                        eleveSelect.innerHTML +=
                            `<option value="${eleve.id}">${eleve.nom} ${eleve.prenom}</option>`;
                    });
                })
                .catch(error => {
                    eleveSelect.innerHTML = '<option value="">Erreur lors du chargement</option>';
                    console.error('Erreur:', error);
                });
        }

        // Pour précharger les élèves si une classe était déjà sélectionnée (par exemple après validation)
        document.addEventListener('DOMContentLoaded', function() {
            const classeSelect = document.getElementById('classe_id');
            if (classeSelect.value) {
                getEleves(classeSelect.value);
            }
        });
    </script>
@endsection
