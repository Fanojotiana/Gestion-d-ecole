@extends('admin.layoutsAdmin.dashboardLayoutsAdmin')

@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">

            {{-- Titre et fil d’Ariane --}}
            <div class="page-header mb-4">
                <h3 class="page-title">Liste des élèves</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Élèves</li>
                </ul>
            </div>

            {{-- Notifications --}}
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

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                    <h5 class="card-title mb-0">Élèves</h5>

                    {{-- Formulaire de filtre et recherche --}}
                    <form action="{{ route('admin.eleves.index') }}" method="GET" class="row g-3 align-items-end mb-3">

                        {{-- Recherche --}}
                        <div class="col-12 col-md-4">
                            <label for="search" class="form-label fw-semibold">Rechercher</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}"
                                class="form-control form-control-sm" placeholder="Nom, prénom, matricule">
                        </div>

                        {{-- Bouton rechercher --}}
                        <div class="col-12 col-md-2 d-grid">
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="fas fa-search me-1"></i>
                            </button>
                        </div>
                        {{-- Filtre classe --}}
                        <div class="col-12 col-md-4">
                            <label for="classe_id" class="form-label fw-semibold">Classe</label>
                            <select name="classe_id" id="classe_id" class="form-select form-select-sm"
                                onchange="this.form.submit()">
                                <option value="">-- Toutes les classes --</option>
                                @foreach ($classes as $classe)
                                    <option value="{{ $classe->id }}"
                                        {{ request('classe_id') == $classe->id ? 'selected' : '' }}>
                                        {{ $classe->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>


                        {{-- Réinitialiser les filtres --}}
                        @if (request('search') || request('classe_id'))
                            <div class="col-12 col-md-2 d-grid">
                                <a href="{{ route('admin.eleves.index') }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-redo-alt me-1"></i>
                                </a>
                            </div>
                        @endif

                    </form>



                    <a href="{{ route('admin.eleves.create') }}" class="btn btn-primary btn-sm ms-3">
                        <i class="fas fa-plus"></i> Ajouter un élève
                    </a>
                </div>

                <div class="card-body p-0">
                    <div style="overflow-x: auto; width: 100%;">
                        <table class="table table-striped table-hover align-middle mb-0" style="min-width: 100%;">
                            <thead class="thead-light">
                                <tr>
                                    <th>Photo</th>
                                    <th>Nom</th>
                                    <th>Prénom</th>
                                    <th>Matricule</th>
                                    <th>Genre</th>
                                    <th>Classe</th>
                                    <th>Date Naissance</th>
                                    <th>Urgence</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($eleves as $eleve)
                                    <tr>
                                        <td>
                                            <img src="{{ $eleve->photo ? asset('storage/' . $eleve->photo) : asset('assets/img/avatar.jpg') }}"
                                                alt="Photo de {{ $eleve->nom }}"
                                                style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%;">
                                        </td>
                                        <td>{{ $eleve->nom }}</td>
                                        <td>{{ $eleve->prenom }}</td>
                                        <td>{{ $eleve->matricule }}</td>
                                        <td>{{ $eleve->genre }}</td>
                                        <td>
                                            {{ $eleve->classe?->nom ?? 'Classe absente' }} -
                                            {{ $eleve->classe?->niveau?->nom ?? 'Niveau absent' }}
                                        </td>
                                        <td>{{ $eleve->date_naissance ? \Carbon\Carbon::parse($eleve->date_naissance)->format('d/m/Y') : '-' }}
                                        </td>
                                        <td>{{ $eleve->urgence_contact ?? '-' }}</td>
                                        <td class="d-flex gap-2">
                                            <button class="btn btn-info btn-sm" title="Détails" data-bs-toggle="modal"
                                                data-bs-target="#eleveModal"
                                                onclick='showDetails(@json($eleve))'>
                                                <i class="fas fa-eye"></i>
                                            </button>

                                            <button class="btn btn-warning btn-sm"
                                                onclick="loadEditModal({{ $eleve->id }})" data-bs-toggle="modal"
                                                data-bs-target="#editEleveModal">
                                                <i class="fas fa-edit"></i>
                                            </button>

                                            <form action="{{ route('admin.eleves.destroy', $eleve) }}" method="POST"
                                                onsubmit="return confirm('Confirmer la suppression ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger btn-sm" title="Supprimer">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">Aucun élève trouvé.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-footer">
                    {{ $eleves->appends(['search' => request('search')])->links() }}
                </div>
            </div>

            {{-- Modale détails élève --}}
            <div class="modal fade" id="eleveModal" tabindex="-1" aria-labelledby="eleveModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Détails de l’élève</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                        </div>
                        <div class="modal-body" id="modal-content">
                            <!-- Injecté dynamiquement par JS -->
                        </div>
                    </div>
                </div>
            </div>

            {{-- Modales édition --}}
            @foreach ($eleves as $eleve)
                <div class="modal fade" id="editModal{{ $eleve->id }}" tabindex="-1"
                    aria-labelledby="editModalLabel{{ $eleve->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            @include('admin.eleves.edit-modal', [
                                'eleve' => $eleve,
                                'classes' => $classes,
                                'niveaux' => $niveaux,
                            ])
                        </div>
                    </div>
                </div>
            @endforeach

            {{-- Modale d’édition dynamique --}}
            <div class="modal fade" id="editEleveModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content" id="edit-eleve-content">
                        <!-- Contenu chargé dynamiquement ici -->
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Style pour éviter le scroll horizontal --}}
    <style>
        .table th,
        .table td {
            white-space: nowrap;
        }
    </style>
@endsection
