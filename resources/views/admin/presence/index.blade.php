@extends('admin.layoutsAdmin.dashboardLayoutsAdmin')

@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">

            {{-- Titre --}}
            <div class="card shadow mb-4 border-0">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0"><i class="fas fa-calendar-check me-2 text-secondary"></i> Feuille de présence
                    </h5>
                </div>
            </div>

            {{-- Formulaire de filtre --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <form method="GET" class="row gy-3 gx-3 align-items-end">
                        <div class="col-md-4">
                            <label for="date" class="form-label fw-semibold">Date</label>
                            <input type="date" id="date" name="date" value="{{ $date }}"
                                class="form-control">
                        </div>

                        <div class="col-md-5">
                            <label for="cours_id" class="form-label fw-semibold">Cours</label>
                            <select name="cours_id" id="cours_id" class="form-select">
                                <option value="">-- Sélectionner un cours --</option>
                                @foreach ($coursList as $cours)
                                    <option value="{{ $cours->id }}" {{ $cours->id == $coursId ? 'selected' : '' }}>
                                        {{ $cours->matiere->nom }} ({{ $cours->classe->nom ?? 'Aucune classe' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3 d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search me-1"></i> Afficher
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Formulaire de présence --}}
            @if ($eleves)
                <form method="POST" action="{{ route('presences.store') }}">
                    @csrf
                    <input type="hidden" name="date" value="{{ $date }}">
                    <input type="hidden" name="cours_id" value="{{ $coursId }}">

                    <div class="card shadow-sm border-0">
                        <div class="card-body table-responsive p-0">
                            <table class="table table-striped table-bordered align-middle text-center mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Élève</th>
                                        <th>Statut</th>
                                        <th>Remarque</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($eleves as $eleve)
                                        <tr>
                                            <td>{{ $eleve->nom }} {{ $eleve->prenom }}</td>
                                            <td>
                                                <select name="presences[{{ $eleve->id }}][statut]" class="form-select">
                                                    <option value="present">Présent</option>
                                                    <option value="absent">Absent</option>
                                                    <option value="retard">Retard</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" name="presences[{{ $eleve->id }}][remarque]"
                                                    class="form-control">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="card-footer text-end bg-white">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-1"></i> Enregistrer
                            </button>
                        </div>
                    </div>
                </form>
            @endif
        </div>
    </div>
@endsection
