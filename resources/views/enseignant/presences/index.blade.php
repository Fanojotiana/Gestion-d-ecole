@extends('enseignant.layoutsEnseignant.dashboardLayoutsEnseignant')

@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">
            <h4 class="mb-4">
                <i class="fas fa-calendar-alt text-primary me-2"></i> Mon emploi du temps
            </h4>

            <div class="table-responsive">
                <table class="table table-bordered align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th>Matière</th>
                            <th>Classe</th>
                            <th>Jour</th>
                            <th>Heure</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cours as $coursItem)
                            <tr>
                                <td>{{ $coursItem->matiere?->nom ?? 'Matière inconnue' }}</td>
                                <td>{{ $coursItem->classe?->nom ?? 'Classe inconnue' }}</td>
                                <td>{{ ucfirst($coursItem->jour) }}</td>
                                <td>{{ \Carbon\Carbon::parse($coursItem->heure_debut)->format('H:i') }} -
                                    {{ \Carbon\Carbon::parse($coursItem->heure_fin)->format('H:i') }}</td>
                                <td>
                                    <a href="{{ route('enseignant.presences.show', $coursItem->id) }}"
                                        class="btn btn-primary btn-sm">
                                        Prendre présence
                                    </a>
                                    <a href="{{ route('enseignant.presences.rapport', $coursItem->id) }}"
                                        class="btn btn-info btn-sm">
                                        Rapport
                                    </a>
                                </td>
                            </tr>
                        @endforeach

                        @if ($cours->isEmpty())
                            <tr>
                                <td colspan="5">Aucun emploi du temps trouvé pour cette classe.</td>
                            </tr>
                        @endif
                    </tbody>

                </table>
            </div>
        </div>
    </div>
@endsection
