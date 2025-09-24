@extends('responsable.layoutsResponsable.dashboardLayoutsResponsable') {{-- adapte selon ton layout --}}

@section('content')
    <div class="container mt-4">
        {{-- Titre --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold"><i class="fas fa-book-open me-2 text-primary"></i> Liste des cours</h4>
            <a href="{{ route('cours.create') }}" class="btn btn-success">
                <i class="fas fa-plus me-1"></i> Nouveau cours
            </a>
        </div>

        {{-- Message de succès --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- Tableau --}}
        <div class="card shadow-sm border-0">
            <div class="card-body table-responsive p-0">
                <table class="table table-bordered table-striped mb-0 align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Matière</th>
                            <th>Classe</th>
                            <th>Enseignant</th>
                            <th>Nom du cours</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($coursList as $cours)
                            <tr>
                                <td>{{ $cours->id }}</td>
                                <td>{{ $cours->matiere->nom ?? '—' }}</td>
                                <td>{{ $cours->classe->nom ?? '—' }}</td>
                                <td>{{ $cours->enseignant->nom ?? '—' }}</td>
                                <td>{{ $cours->nom ?? '—' }}</td>
                                <td>{{ $cours->description ?? '—' }}</td>
                                <td>
                                    <a href="{{ route('cours.edit', $cours->id) }}" class="btn btn-sm btn-warning me-1">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form action="{{ route('cours.destroy', $cours->id) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Confirmer la suppression ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">Aucun cours disponible.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
