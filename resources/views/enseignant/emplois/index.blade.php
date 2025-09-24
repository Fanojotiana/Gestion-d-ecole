@extends('enseignant.layoutsEnseignant.dashboardLayoutsEnseignant')

@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">

            {{-- En-tête --}}
            <div class="card shadow border-0 mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap">
                    <h4 class="mb-0 fw-semibold">
                        <i class="fas fa-calendar-alt me-2 text-secondary"></i> Mon emploi du temps
                    </h4>
                </div>
            </div>

            {{-- Filtres --}}
            <form method="GET" action="{{ route('enseignant.emplois.index') }}" class="mb-4">
                <div class="row g-2 align-items-center">
                    {{-- Filtre classe --}}
                    <div class="col-auto">
                        <label for="classe_id" class="col-form-label fw-semibold">Choisir une classe :</label>
                    </div>
                    <div class="col-auto" style="min-width: 250px;">
                        <select name="classe_id" id="classe_id" class="form-select" onchange="this.form.submit()">
                            <option value="">-- Sélection de la classe --</option>
                            @foreach ($classes as $classe)
                                <option value="{{ $classe->id }}" {{ $classeId == $classe->id ? 'selected' : '' }}>
                                    {{ $classe->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Filtre semaine --}}
                    <div class="col-auto">
                        <label for="semaine" class="col-form-label fw-semibold">Semaine :</label>
                    </div>
                    <div class="col-auto" style="min-width: 150px;">
                        <select name="semaine" id="semaine" class="form-select" onchange="this.form.submit()"
                            {{ empty($classeId) ? 'disabled' : '' }}>
                            <option value="">-- Toutes les semaines --</option>
                            @foreach ($semainesDisponibles as $sem)
                                <option value="{{ $sem }}" {{ $semaine == $sem ? 'selected' : '' }}>
                                    Semaine {{ $sem }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>

            {{-- Emploi du temps --}}
            @if (!empty($classeId))
                @php
                    $emploisClasse = $emploisDuTemps->where('classe_id', $classeId);

                    if (!empty($semaine)) {
                        $emploisClasse = $emploisClasse->where('semaine', intval($semaine));
                    }

                    $joursSemaine = ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi'];

                    $creneaux = $emploisClasse
                        ->map(function ($e) {
                            return $e->heure_debut . '-' . $e->heure_fin;
                        })
                        ->unique()
                        ->sort()
                        ->values();

                    $emploiMap = [];
                    foreach ($emploisClasse as $emploi) {
                        $key = $emploi->heure_debut . '-' . $emploi->heure_fin;
                        $jour = strtolower($emploi->jour);
                        $emploiMap[$jour][$key] = $emploi;
                    }
                @endphp

                <h5 class="mb-3 fw-semibold">📘 Classe sélectionnée :
                    {{ $classes->firstWhere('id', $classeId)->nom ?? '' }}
                </h5>

                @if ($emploisClasse->isEmpty())
                    <div class="alert alert-warning">Aucun emploi du temps enregistré pour cette classe et cette semaine.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover text-center align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Heure / Jour</th>
                                    @foreach ($joursSemaine as $jour)
                                        <th>{{ ucfirst($jour) }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($creneaux as $creneau)
                                    @php
                                        [$debut, $fin] = explode('-', $creneau);
                                    @endphp
                                    <tr>
                                        <td class="fw-semibold text-nowrap">
                                            {{ \Carbon\Carbon::parse($debut)->format('H:i') }} -
                                            {{ \Carbon\Carbon::parse($fin)->format('H:i') }}
                                        </td>
                                        @foreach ($joursSemaine as $jour)
                                            @php
                                                $emploi = $emploiMap[strtolower($jour)][$creneau] ?? null;
                                            @endphp
                                            <td>
                                                @if ($emploi)
                                                    <div class="bg-info bg-opacity-25 rounded p-2 small">
                                                        <strong>{{ $emploi->matiere->nom ?? '???' }}</strong><br>
                                                        <small>{{ $emploi->classe->nom ?? '' }}</small>
                                                    </div>
                                                @else
                                                    <span class="text-muted fst-italic">–</span>
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            @else
                <div class="alert alert-info mt-4">
                    Veuillez sélectionner une classe pour afficher votre emploi du temps.
                </div>
            @endif
        </div>
    </div>
@endsection
