@extends('admin.layoutsAdmin.dashboardLayoutsAdmin')

@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">

            {{-- En-tête --}}
            <div class="card shadow border-0 mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap">
                    <h4 class="mb-0 fw-semibold">
                        <i class="fas fa-calendar-alt me-2 text-secondary"></i> Emploi du temps
                    </h4>
                    <a href="{{ route('admin.emplois-du-temps.create') }}" class="btn btn-sm btn-success shadow-sm">
                        <i class="fas fa-plus me-1"></i> Ajouter un créneau
                    </a>
                </div>
            </div>

            {{-- Filtres --}}
            <form method="GET" action="{{ route('admin.emplois-du-temps.index') }}" class="mb-4">
                <div class="row g-2 align-items-center">
                    {{-- Filtre classe --}}
                    <div class="col-auto">
                        <label for="classe_id" class="col-form-label fw-semibold">Choisir une classe :</label>
                    </div>
                    <div class="col-auto" style="min-width: 250px;">
                        <select name="classe_id" id="classe_id" class="form-select" onchange="this.form.submit()">
                            <option value="">-- Sélectionnez une classe --</option>
                            @foreach ($niveaux as $niveau)
                                <optgroup label="{{ $niveau->nom }}">
                                    @foreach ($niveau->classes as $classe)
                                        <option value="{{ $classe->id }}"
                                            {{ request('classe_id') == $classe->id ? 'selected' : '' }}>
                                            {{ $classe->nom }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>

                    {{-- Filtre semaine --}}
                    <div class="col-auto">
                        <label for="semaine" class="col-form-label fw-semibold">Semaine :</label>
                    </div>
                    <div class="col-auto" style="min-width: 150px;">
                        <select name="semaine" id="semaine" class="form-select" onchange="this.form.submit()"
                            {{ empty($classeSelectionnee) ? 'disabled' : '' }}>
                            <option value="">-- Toutes les semaines --</option>
                            @foreach ($semainesDisponibles as $sem)
                                <option value="{{ $sem }}" {{ request('semaine') == $sem ? 'selected' : '' }}>
                                    Semaine {{ $sem }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @if ($classeSelectionnee && request('semaine'))
                    <div class="mt-3">
                        <a href="{{ route('admin.emplois-du-temps.pdf', request('semaine')) }}?classe_id={{ $classeSelectionnee->id }}"
                            target="_blank" class="btn btn-primary">
                            <i class="fas fa-file-pdf"></i> Exporter la semaine {{ request('semaine') }} en PDF
                        </a>
                    </div>
                @endif
            </form>

            {{-- Emploi du temps --}}
            @if ($classeSelectionnee)
                @php
                    $emploisClasse = $emploisDuTemps->where('classe_id', $classeSelectionnee->id);

                    // ⚠️ Ajout du filtrage de semaine réel
                    if (request()->filled('semaine')) {
                        $emploisClasse = $emploisClasse->where('semaine', intval(request('semaine')));
                    }

                    $emploisParSemaine = $emploisClasse->groupBy('semaine')->sortKeys();
                    $joursSemaine = ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi'];
                    $creneaux = $emploisClasse->map(fn($e) => $e->heure_debut . '-' . $e->heure_fin)->unique()->sort();

                    $emploiMapParSemaine = [];
                    foreach ($emploisClasse as $emploi) {
                        $key = $emploi->heure_debut . '-' . $emploi->heure_fin;
                        $emploiMapParSemaine[$emploi->semaine][strtolower($emploi->jour)][$key] = $emploi;
                    }
                @endphp

                <h5 class="mb-3 fw-semibold">📘 Classe sélectionnée : {{ $classeSelectionnee->nom }}</h5>

                @if ($emploisClasse->isEmpty())
                    <div class="alert alert-warning">Aucun emploi du temps enregistré pour cette classe.</div>
                @else
                    {{-- Onglets semaines --}}
                    <ul class="nav nav-tabs mb-3" id="semaineTabs" role="tablist">
                        @foreach ($emploisParSemaine as $semaine => $emplois)
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="tab-semaine-{{ $semaine }}" data-bs-toggle="tab"
                                    data-bs-target="#semaine-{{ $semaine }}" type="button" role="tab"
                                    aria-selected="true">
                                    Semaine {{ $semaine }}
                                </button>
                            </li>
                        @break
                    @endforeach
                </ul>

                {{-- Contenu des semaines --}}
                <div class="tab-content" id="semaineTabsContent">
                    @foreach ($emploisParSemaine as $semaine => $emploisSemaine)
                        <div class="tab-pane fade show active" id="semaine-{{ $semaine }}" role="tabpanel"
                            aria-labelledby="tab-semaine-{{ $semaine }}">

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
                                            <tr>
                                                <td class="fw-semibold text-nowrap">
                                                    {{ \Carbon\Carbon::parse(explode('-', $creneau)[0])->format('H:i') }}
                                                    -
                                                    {{ \Carbon\Carbon::parse(explode('-', $creneau)[1])->format('H:i') }}
                                                </td>
                                                @foreach ($joursSemaine as $jour)
                                                    @php
                                                        $emploi =
                                                            $emploiMapParSemaine[$semaine][strtolower($jour)][
                                                                $creneau
                                                            ] ?? null;
                                                    @endphp
                                                    <td>
                                                        @if ($emploi)
                                                            <div class="bg-info bg-opacity-25 rounded p-2 small">
                                                                <strong>{{ $emploi->matiere->nom }}</strong><br>
                                                                <small>{{ $emploi->enseignant->nom }}
                                                                    {{ $emploi->enseignant->prenom }}</small>
                                                                <div class="mt-1 d-flex gap-1 justify-content-center">
                                                                    <a href="{{ route('admin.emplois-du-temps.edit', $emploi->id) }}"
                                                                        class="btn btn-sm btn-outline-warning"
                                                                        title="Modifier">
                                                                        <i class="fas fa-edit"></i>
                                                                    </a>
                                                                    <form
                                                                        action="{{ route('admin.emplois-du-temps.destroy', $emploi->id) }}"
                                                                        method="POST"
                                                                        onsubmit="return confirm('Supprimer ce créneau ?')">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit"
                                                                            class="btn btn-sm btn-outline-danger"
                                                                            title="Supprimer">
                                                                            <i class="fas fa-trash-alt"></i>
                                                                        </button>
                                                                    </form>
                                                                </div>
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

                        </div>
                    @break
                @endforeach
            </div>
        @endif
    @else
        <div class="alert alert-info mt-4">
            Veuillez sélectionner une classe pour afficher son emploi du temps.
        </div>
    @endif

</div>
</div>
@endsection
