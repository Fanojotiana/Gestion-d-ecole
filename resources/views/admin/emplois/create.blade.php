@extends('admin.layoutsAdmin.dashboardLayoutsAdmin')

@section('title', 'Ajouter un créneau')

@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">

            {{-- Titre et fil d’ariane --}}
            <div class="page-header mb-4">
                <h3 class="page-title">Ajouter un créneau</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.emplois-du-temps.index') }}">Emploi du temps</a></li>
                    <li class="breadcrumb-item active">Ajouter</li>
                </ul>
            </div>

            {{-- Formulaire --}}
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.emplois-du-temps.store') }}" method="POST">
                        @csrf

                        {{-- Niveau --}}
                        <div class="mb-3">
                            <label for="niveau_id" class="form-label">Niveau</label>
                            <select name="niveau_id" id="niveau_id"
                                class="form-select @error('niveau_id') is-invalid @enderror">
                                <option value="">-- Sélectionner un niveau --</option>
                                @foreach ($niveaux as $niveau)
                                    <option value="{{ $niveau->id }}"
                                        {{ old('niveau_id') == $niveau->id ? 'selected' : '' }}>
                                        {{ $niveau->nom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('niveau_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Classe --}}
                        <div class="mb-3">
                            <label for="classe_id" class="form-label">Classe</label>
                            <select name="classe_id" id="classe_id"
                                class="form-select @error('classe_id') is-invalid @enderror">
                                <option value="">-- Sélectionner une classe --</option>
                                @foreach ($classes as $classe)
                                    <option value="{{ $classe->id }}"
                                        {{ old('classe_id') == $classe->id ? 'selected' : '' }}>
                                        {{ $classe->nom }} - {{ $classe->niveau->nom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('classe_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Matière --}}
                        <div class="mb-3">
                            <label for="matiere_id" class="form-label">Matière</label>
                            <select name="matiere_id" id="matiere_id"
                                class="form-select @error('matiere_id') is-invalid @enderror" required>
                                <option value="">-- Sélectionner une matière --</option>
                                @foreach ($matieres as $matiere)
                                    <option value="{{ $matiere->id }}"
                                        {{ old('matiere_id') == $matiere->id ? 'selected' : '' }}>
                                        {{ $matiere->nom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('matiere_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Enseignant (auto rempli) --}}
                        <div class="mb-3">
                            <label for="enseignant_id" class="form-label">Enseignant</label>
                            <select name="enseignant_id" id="enseignant_id"
                                class="form-select @error('enseignant_id') is-invalid @enderror" required>
                                <option value="">-- Sélectionner une matière d'abord --</option>
                            </select>
                            @error('enseignant_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Jour --}}
                        <div class="mb-3">
                            <label for="jour" class="form-label">Jour</label>
                            <select name="jour" id="jour" class="form-select @error('jour') is-invalid @enderror">
                                <option value="">-- Sélectionner un jour --</option>
                                @foreach ($jours as $jour)
                                    <option value="{{ $jour }}" {{ old('jour') == $jour ? 'selected' : '' }}>
                                        {{ ucfirst($jour) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('jour')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        {{-- Semaine --}}
                        <div class="mb-3">
                            <label for="semaine" class="form-label">Semaine</label>
                            <select name="semaine" id="semaine" class="form-select @error('semaine') is-invalid @enderror"
                                required>
                                <option value="">-- Sélectionner une semaine --</option>
                                @for ($i = 1; $i <= 5; $i++)
                                    <option value="{{ $i }}" {{ old('semaine') == $i ? 'selected' : '' }}>
                                        Semaine {{ $i }}</option>
                                @endfor
                            </select>
                            @error('semaine')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>


                        {{-- Heures --}}
                        <div class="mb-3">
                            <label for="heure_debut" class="form-label">Heure de début</label>
                            <select name="heure_debut" id="heure_debut" class="form-select">
                                @foreach ($heures as $heure)
                                    <option value="{{ $heure }}"
                                        {{ old('heure_debut') == $heure ? 'selected' : '' }}>
                                        {{ $heure }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="heure_fin" class="form-label">Heure de fin</label>
                            <select name="heure_fin" id="heure_fin" class="form-select">
                                @foreach ($heures as $heure)
                                    <option value="{{ $heure }}"
                                        {{ old('heure_fin') == $heure ? 'selected' : '' }}>
                                        {{ $heure }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Boutons --}}
                        <div class="d-flex justify-content-end">
                            <a href="{{ route('admin.emplois-du-temps.index') }}"
                                class="btn btn-secondary me-2">Annuler</a>
                            <button type="submit" class="btn btn-primary">Ajouter</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    {{-- Classe dynamique par niveau --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const niveauSelect = document.getElementById('niveau_id');
            const classeSelect = document.getElementById('classe_id');

            niveauSelect.addEventListener('change', function() {
                const niveauId = this.value;

                classeSelect.innerHTML = '<option value="">Chargement...</option>';

                fetch(`/admin/niveaux/${niveauId}/classes`)
                    .then(res => res.json())
                    .then(data => {
                        classeSelect.innerHTML =
                            '<option value="">-- Sélectionner une classe --</option>';
                        data.forEach(classe => {
                            const option = document.createElement('option');
                            option.value = classe.id;
                            option.textContent = classe.nom + ' - ' + classe.niveau_nom;
                            classeSelect.appendChild(option);
                        });
                    })
                    .catch(() => {
                        classeSelect.innerHTML = '<option value="">Erreur de chargement</option>';
                    });
            });
        });
    </script>

    {{-- Chargement de l’enseignant automatiquement via la matière --}}

    {{-- Classe dynamique par niveau --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const niveauSelect = document.getElementById('niveau_id');
            const classeSelect = document.getElementById('classe_id');

            niveauSelect.addEventListener('change', function() {
                const niveauId = this.value;

                classeSelect.innerHTML = '<option value="">Chargement...</option>';

                fetch(`/admin/niveaux/${niveauId}/classes`)
                    .then(res => res.json())
                    .then(data => {
                        classeSelect.innerHTML =
                            '<option value="">-- Sélectionner une classe --</option>';
                        data.forEach(classe => {
                            const option = document.createElement('option');
                            option.value = classe.id;
                            option.textContent = classe.nom + ' - ' + classe.niveau_nom;
                            classeSelect.appendChild(option);
                        });
                    })
                    .catch(() => {
                        classeSelect.innerHTML = '<option value="">Erreur de chargement</option>';
                    });
            });

            // 🎯 Correction ici : définition des variables
            const matiereSelect = document.getElementById("matiere_id");
            const enseignantSelect = document.getElementById("enseignant_id");

            matiereSelect.addEventListener("change", function() {
                const matiereId = this.value;
                enseignantSelect.innerHTML = '<option value="">Chargement...</option>';

                fetch(`/admin/enseignant-par-matiere/${matiereId}`)
                    .then(response => {
                        if (!response.ok) throw new Error('Aucun enseignant trouvé');
                        return response.json();
                    })
                    .then(data => {
                        enseignantSelect.innerHTML =
                            '<option value="">-- Sélectionner un enseignant --</option>';
                        data.forEach(enseignant => {
                            const option = document.createElement('option');
                            option.value = enseignant.id;
                            option.textContent = `${enseignant.nom} ${enseignant.prenom}`;
                            enseignantSelect.appendChild(option);
                        });
                    })
                    .catch(error => {
                        enseignantSelect.innerHTML =
                            '<option value="">Aucun enseignant disponible</option>';
                        console.error(error);
                    });
            });
        });
    </script>


@endsection
