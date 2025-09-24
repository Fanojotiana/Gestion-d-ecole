@extends('admin.layoutsAdmin.dashboardLayoutsAdmin')

@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">

            {{-- Titre et fil d’Ariane --}}
            <div class="page-header mb-4">
                <h3 class="page-title">Liste des responsables</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Responsables</li>
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
                    <h5 class="card-title mb-0">Responsables</h5>

                    {{-- Formulaire recherche --}}
                    <form action="{{ route('admin.responsables.index') }}" method="GET" class="d-flex mb-2 mb-md-0">
                        <input type="text" name="search" class="form-control form-control-sm"
                            placeholder="Rechercher..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary btn-sm ms-2"><i class="fas fa-search"></i></button>
                    </form>

                    <a href="{{ route('admin.responsables.create') }}" class="btn btn-primary btn-sm ms-3">
                        <i class="fas fa-plus"></i> Ajouter un responsable
                    </a>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Photo</th>
                                    <th>Nom</th>
                                    <th>Prénom</th>
                                    <th>Date de naissance</th>
                                    <th>Sexe</th>
                                    <th>Téléphone</th>
                                    <th>Email</th>
                                    <th>Type</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($responsables as $responsable)
                                    <tr>
                                        <td>
                                            <img src="{{ $responsable->photo ? asset('storage/' . $responsable->photo) : asset('assets/img/avatar.jpg') }}"
                                                alt="Photo de {{ $responsable->nom }}"
                                                style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%;">
                                        </td>
                                        <td>{{ $responsable->nom }}</td>
                                        <td>{{ $responsable->prenom }}</td>
                                        <td>{{ \Carbon\Carbon::parse($responsable->date_naissance)->format('d/m/Y') }}</td>
                                        <td>{{ $responsable->sexe }}</td>
                                        <td>{{ $responsable->telephone }}</td>
                                        <td>{{ $responsable->email }}</td>
                                        <td>{{ ucfirst($responsable->responsable_type) }}</td>
                                        <td class="d-flex gap-2">
                                            <button class="btn btn-info btn-sm" title="Détails" data-bs-toggle="modal"
                                                data-bs-target="#responsableModal"
                                                onclick="showDetails({{ $responsable->toJson() }})">
                                                <i class="fas fa-eye"></i>
                                            </button>

                                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#editModal{{ $responsable->id }}" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </button>

                                            <form action="{{ route('admin.responsables.destroy', $responsable->id) }}"
                                                method="POST" onsubmit="return confirm('Confirmer la suppression ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" title="Supprimer">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">Aucun responsable trouvé.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-footer">
                    {{ $responsables->appends(['search' => request('search')])->links() }}
                </div>
            </div>

            {{-- Modal show (unique) --}}
            <div class="modal fade" id="responsableModal" tabindex="-1" aria-labelledby="responsableModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="responsableModalLabel">Détails du responsable</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                        </div>
                        <div class="modal-body" id="modal-content">
                            <!-- Contenu injecté par JS -->
                        </div>
                    </div>
                </div>
            </div>

            {{-- Modales édition (une par responsable) --}}
            @foreach ($responsables as $responsable)
                <div class="modal fade" id="editModal{{ $responsable->id }}" tabindex="-1"
                    aria-labelledby="editModalLabel{{ $responsable->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            @include('admin.responsable.edit-modal', ['responsable' => $responsable])
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>
@endsection
