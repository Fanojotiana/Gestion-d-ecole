@extends('admin.layoutsAdmin.dashboardLayoutsAdmin')

@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">

            <div class="card shadow border-0 mb-4">
                <div class="card-body">
                    <h4 class="fw-bold mb-3"><i class="fas fa-trophy text-warning me-2"></i> Classement des élèves</h4>

                    {{-- Formulaire filtre --}}
                    <form method="GET" action="{{ route('admin.rang.eleves') }}" class="row g-3 align-items-end mb-3">
                        <div class="col-md-5">
                            <label for="classe_id" class="form-label fw-semibold">Classe</label>
                            <select name="classe_id" id="classe_id" class="form-select" onchange="this.form.submit()">
                                <option value="">-- Toutes les classes --</option>
                                @foreach ($classes as $classe)
                                    <option value="{{ $classe->id }}"
                                        {{ (int) $classeId === $classe->id ? 'selected' : '' }}>
                                        {{ $classe->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-5">
                            <label for="periode" class="form-label fw-semibold">Période</label>
                            <select name="periode" id="periode" class="form-select" onchange="this.form.submit()">
                                @foreach (['Trimestre 1', 'Trimestre 2', 'Trimestre 3'] as $trim)
                                    <option value="{{ $trim }}" {{ $periode === $trim ? 'selected' : '' }}>
                                        {{ $trim }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        @if ($classeId || $periode)
                            <div class="col-md-2">
                                <a href="{{ route('admin.rang.eleves') }}" class="btn btn-outline-secondary w-100">
                                    Réinitialiser
                                </a>
                            </div>
                        @endif
                    </form>

                    {{-- Titre --}}
                    <div class="mb-3">
                        <h6 class="text-muted">
                            Résultats pour :
                            <strong>{{ $classes->firstWhere('id', $classeId)?->nom ?? 'toutes les classes' }}</strong>,
                            <strong>{{ $periode }}</strong>
                        </h6>
                    </div>

                    {{-- Tableau --}}
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle text-center">
                            <thead class="table-light">
                                <tr>
                                    <th>Rang</th>
                                    <th>Élève</th>
                                    <th>Classe</th>
                                    <th>Moyenne</th>
                                    <th>Tableau d'honneur</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($eleves as $eleve)
                                    <tr>
                                        <td>{{ $eleve->rang }}</td>
                                        <td>{{ $eleve->nom }} {{ $eleve->prenom }}</td>
                                        <td>{{ $eleve->classe?->nom ?? '-' }}</td>
                                        <td>
                                            <strong
                                                class="
                                                @if ($eleve->moyenne < 12) text-danger
                                                @elseif ($eleve->moyenne < 15)
                                                    text-warning
                                                @else
                                                    text-success @endif
                                            ">
                                                {{ number_format($eleve->moyenne, 2) }}
                                            </strong>
                                        </td>
                                        <td>
                                            @if ($eleve->tableau_honneur)
                                                <span class="badge bg-success">Oui</span>
                                            @else
                                                <span class="text-muted">–</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-muted">Aucun élève trouvé pour cette période.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
