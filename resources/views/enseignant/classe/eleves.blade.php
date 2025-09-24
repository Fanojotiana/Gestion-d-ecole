@extends('enseignant.layoutsEnseignant.dashboardLayoutsEnseignant')

@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">

            <div class="card shadow border-0">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0 fw-bold">
                        <i class="fas fa-users text-primary me-2"></i>
                        Liste des élèves – {{ $classe->nom ?? 'Classe inconnue' }}
                    </h4>
                </div>

                <div class="card-body">
                    @if ($eleves->isEmpty())
                        <div class="alert alert-info">Aucun élève inscrit dans cette classe.</div>
                    @else
                        {{-- Formulaire de présence --}}
                        <form method="POST" action="{{ route('enseignant.presences.store', $emploiDuTemps->id) }}">
                            @csrf
                            <input type="hidden" name="emploi_du_temps_id" value="{{ $emploiDuTemps->id }}">

                            {{-- Informations du cours --}}
                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <label for="date" class="form-label fw-semibold">Date du cours :</label>
                                    <input type="date" name="date" id="date" class="form-control"
                                        value="{{ old('date', date('Y-m-d')) }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Jour :</label>
                                    <div class="form-control bg-light">{{ ucfirst($emploiDuTemps->jour ?? '-') }}</div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Heure :</label>
                                    <div class="form-control bg-light">
                                        {{ \Carbon\Carbon::parse($emploiDuTemps->heure_debut)->format('H\hi') ?? '-' }}
                                        -
                                        {{ \Carbon\Carbon::parse($emploiDuTemps->heure_fin)->format('H\hi') ?? '-' }}
                                    </div>
                                </div>
                            </div>

                            {{-- Tableau de présence --}}
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover text-center align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Photo</th>
                                            <th>Nom de l’élève</th>
                                            <th>Présent</th>
                                            <th>Absent</th>
                                            <th>Retard</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($eleves as $index => $eleve)
                                            @php
                                                $presence = $presences->get($eleve->id);
                                                $statut = $presence ? $presence->statut : null;
                                            @endphp
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    @if ($eleve->photo)
                                                        <img src="{{ asset('storage/' . $eleve->photo) }}"
                                                            alt="Photo de {{ $eleve->nom }}" class="rounded-circle"
                                                            width="40" height="40">
                                                    @else
                                                        <span class="text-muted">—</span>
                                                    @endif
                                                </td>
                                                <td>{{ $eleve->nom }}</td>
                                                <td>
                                                    <input type="radio" name="presences[{{ $eleve->id }}]"
                                                        value="present" {{ $statut === 'present' ? 'checked' : '' }}
                                                        required>
                                                </td>
                                                <td>
                                                    <input type="radio" name="presences[{{ $eleve->id }}]"
                                                        value="absent" {{ $statut === 'absent' ? 'checked' : '' }}>
                                                </td>
                                                <td>
                                                    <input type="radio" name="presences[{{ $eleve->id }}]"
                                                        value="retard" {{ $statut === 'retard' ? 'checked' : '' }}>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-4 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary shadow-sm">
                                    <i class="fas fa-save me-1"></i> Enregistrer les présences
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
