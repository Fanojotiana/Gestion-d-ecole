@php
    abort_unless(auth()->user()?->role === 'admin', 403);
@endphp

@extends('admin.layoutsAdmin.dashboardLayoutsAdmin')

{{-- CSS personnalisé pour les badges de niveaux --}}
@section('styles')
    <style>
        .badge-niveau-1 {
            background-color: #55efc4 !important;
            color: #000 !important;
        }

        .badge-niveau-2 {
            background-color: #2719e2 !important;
            color: #fff !important;
        }

        .badge-niveau-3 {
            background-color: #dfd000 !important;
            color: #000 !important;
        }

        .badge-niveau-4 {
            background-color: #00c400 !important;
            color: #fff !important;
        }

        .badge-niveau-5 {
            background-color: #cf3c1b !important;
            color: #fff !important;
        }

        .badge-niveau-6 {
            background-color: #8009af !important;
            color: #fff !important;
        }

        .badge-niveau-7 {
            background-color: #000 !important;
            color: #fff !important;
        }
    </style>
@endsection

@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">

            {{-- Notifications Toastr --}}
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

            {{-- Formulaire d'ajout de matière --}}
            {{-- Formulaire d'ajout de matière --}}
            <div class="card p-4 mb-5">
                <h5 class="card-title">Ajouter une matière</h5>
                <form action="{{ route('admin.matieres.store') }}" method="POST">
                    @csrf

                    <div class="row gx-3 gy-2">
                        {{-- Nom --}}
                        <div class="col-md-6">
                            <label for="nom" class="form-label">Nom de la matière <span
                                    class="text-danger">*</span></label>
                            <input type="text" id="nom" name="nom" class="form-control" required
                                value="{{ old('nom') }}">
                            @error('nom')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Sélection des niveaux (multi-select) --}}
                        <div class="col-md-6">
                            <label for="niveaux" class="form-label">Niveaux associés <span
                                    class="text-danger">*</span></label>
                            <select name="niveaux[]" id="niveaux" class="form-select" multiple required>
                                @foreach ($niveaux as $niveau)
                                    <option value="{{ $niveau->id }}"
                                        {{ collect(old('niveaux'))->contains($niveau->id) ? 'selected' : '' }}>
                                        {{ $niveau->nom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('niveaux')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Sélection des enseignants --}}
                        <div class="col-md-12">
                            <label for="enseignants" class="form-label">Enseignants associés</label>
                            <select name="enseignants[]" id="enseignants" class="form-select" multiple>
                                @foreach ($enseignants as $ens)
                                    <option value="{{ $ens->id }}"
                                        {{ collect(old('enseignants'))->contains($ens->id) ? 'selected' : '' }}>
                                        {{ $ens->nom }} {{ $ens->prenom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('enseignants')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>


            {{-- Liste des matières --}}
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                    <h5 class="card-title mb-0">Liste des matières</h5>

                    <form action="{{ route('admin.matieres.index') }}" method="GET" class="d-flex mb-2 mb-md-0"
                        role="search">
                        <input type="text" name="search" class="form-control form-control-sm"
                            placeholder="Rechercher..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary btn-sm ms-2">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">

                        @php
                            // Association id niveau => classe badge-niveau-X
                            $niveauBadgeIndex = [];
                            foreach ($niveaux as $index => $niv) {
                                $niveauBadgeIndex[$niv->id] = 'badge-niveau-' . (($index % 7) + 1);
                            }
                        @endphp

                        <table class="table table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nom</th>
                                    <th>Niveau</th>
                                    <th>Enseignants</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($matieres as $matiere)
                                    <tr>
                                        <td>{{ $matiere->id }}</td>
                                        <td><span class="badge bg-info fs-6 px-3 py-2">{{ $matiere->nom }}</span></td>

                                        <td>
                                            @foreach ($matiere->niveaux as $niveau)
                                                @php
                                                    $badgeClass = $niveauBadgeIndex[$niveau->id] ?? 'badge-primary';
                                                @endphp
                                                <span class="badge {{ $badgeClass }}">{{ $niveau->nom }}</span>
                                            @endforeach
                                        </td>

                                        <td>
                                            @if ($matiere->enseignants->count())
                                                <div class="d-flex flex-wrap gap-1">
                                                    @foreach ($matiere->enseignants as $ens)
                                                        <span class="badge bg-secondary">{{ $ens->nom }}
                                                            {{ $ens->prenom }}</span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <em>Aucun enseignant</em>
                                            @endif
                                        </td>


                                        {{-- Actions (modifier, supprimer) ici --}}
                                        <td>
                                            {{-- Modifier (ouvre un modal) --}}
                                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                                data-bs-target="#editMatiereModal{{ $matiere->id }}">
                                                <i class="fas fa-edit"></i>
                                            </button>

                                            {{-- Supprimer (formulaire DELETE) --}}
                                            <form action="{{ route('admin.matieres.destroy', $matiere->id) }}"
                                                method="POST" style="display: inline-block;"
                                                onsubmit="return confirm('Confirmer la suppression ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>


                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">
                                            @if (request('search'))
                                                Aucune matière trouvée pour « {{ request('search') }} ».
                                            @else
                                                Aucune matière enregistrée.
                                            @endif
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        @foreach ($matieres as $matiere)
                            <!-- Modal d'édition -->
                            <div class="modal fade" id="editMatiereModal{{ $matiere->id }}" tabindex="-1"
                                aria-labelledby="editModalLabel{{ $matiere->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <form action="{{ route('admin.matieres.update', $matiere->id) }}" method="POST"
                                        class="modal-content">
                                        @csrf
                                        @method('PUT')

                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editModalLabel{{ $matiere->id }}">Modifier la
                                                matière</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Fermer"></button>
                                        </div>

                                        <div class="modal-body">
                                            {{-- Nom --}}
                                            <div class="mb-3">
                                                <label for="nom{{ $matiere->id }}" class="form-label">Nom de la
                                                    matière</label>
                                                <input type="text" id="nom{{ $matiere->id }}" name="nom"
                                                    class="form-control" required value="{{ $matiere->nom }}">
                                            </div>

                                            {{-- Niveaux (multi-select) --}}
                                            <div class="mb-3">
                                                <label for="niveaux{{ $matiere->id }}" class="form-label">Niveaux
                                                    associés</label>
                                                <select name="niveaux[]" id="niveaux{{ $matiere->id }}"
                                                    class="form-select" multiple required>
                                                    @foreach ($niveaux as $niveau)
                                                        <option value="{{ $niveau->id }}"
                                                            {{ $matiere->niveaux->contains($niveau->id) ? 'selected' : '' }}>
                                                            {{ $niveau->nom }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            {{-- Enseignants --}}
                                            <div class="mb-3">
                                                <label for="enseignants{{ $matiere->id }}"
                                                    class="form-label">Enseignants associés</label>
                                                <select name="enseignants[]" id="enseignants{{ $matiere->id }}"
                                                    class="form-select" multiple>
                                                    @foreach ($enseignants as $ens)
                                                        <option value="{{ $ens->id }}"
                                                            {{ $matiere->enseignants->contains($ens->id) ? 'selected' : '' }}>
                                                            {{ $ens->nom }} {{ $ens->prenom }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Annuler</button>
                                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endforeach



                    </div>
                </div>

                <div class="card-footer">
                    {{ $matieres->appends(['search' => request('search')])->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
