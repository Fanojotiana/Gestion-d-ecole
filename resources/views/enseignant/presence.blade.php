@extends('enseignant.layoutsEnseignant.dashboardLayoutsEnseignant')

@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">
            <h4 class="mb-4"><i class="fas fa-calendar-check text-primary me-2"></i> Mes cours à gérer</h4>

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th>Matière</th>
                            <th>Classe</th>
                            <th>Jour</th>
                            <th>Heure</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cours as $c)
                            <tr>
                                <td>{{ $c->matiere?->nom ?? 'Matière inconnue' }}</td>
                                <td>{{ $c->classe?->nom ?? 'Classe inconnue' }}</td>
                                <td>{{ ucfirst($c->jour) }}</td>
                                <td>{{ \Carbon\Carbon::parse($c->heure_debut)->format('H:i') }} -
                                    {{ \Carbon\Carbon::parse($c->heure_fin)->format('H:i') }}</td>
                                <td>
                                    <a href="{{ route('enseignant.presences.liste', ['classe' => $c->classe_id, 'matiere' => $c->matiere_id]) }}"
                                        class="btn btn-sm btn-primary">
                                        <i class="fas fa-user-check me-1"></i> Gérer présence
                                    </a>

                                </td>
                            </tr>
                        @endforeach

                        @if ($cours->isEmpty())
                            <tr>
                                <td colspan="5"><em>Aucun cours assigné pour l’instant.</em></td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
