@extends('enseignant.layoutsEnseignant.dashboardLayoutsEnseignant')

@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4><i class="fas fa-clipboard-check text-primary me-2"></i> Présences -
                    {{ $cours->matiere?->nom ?? 'Matière' }} ({{ $cours->classe?->nom ?? 'Classe' }})</h4>
                <a href="{{ route('enseignant.presences.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Retour
                </a>
            </div>

            <!-- Sélecteur de date -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h5 class="mb-0">Date : {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</h5>
                            <small
                                class="text-muted">{{ \Carbon\Carbon::parse($date)->locale('fr')->translatedFormat('l') }}</small>
                        </div>
                        <div class="col-md-6 text-end">
                            <div class="d-inline-flex align-items-center">
                                <label class="me-2">Changer de date :</label>
                                <input type="date" id="dateSelector" value="{{ $date }}"
                                    class="form-control me-2" style="width: auto;">
                                <button onclick="changerDate()" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-calendar me-1"></i> Aller
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('enseignant.presences.store') }}">
                @csrf
                <input type="hidden" name="cours_id" value="{{ $cours->id }}">
                <input type="hidden" name="date" value="{{ $date }}">

                <!-- Actions rapides -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <button type="button" class="btn btn-outline-success btn-sm me-2"
                                    onclick="marquerTousPresents()">
                                    <i class="fas fa-check-circle me-1"></i> Tous présents
                                </button>
                                <button type="button" class="btn btn-outline-danger btn-sm" onclick="marquerTousAbsents()">
                                    <i class="fas fa-times-circle me-1"></i> Tous absents
                                </button>
                            </div>
                            <div class="d-flex gap-3">
                                <div class="text-center">
                                    <div class="badge bg-success fs-6" id="presents">0</div>
                                    <small class="d-block">Présents</small>
                                </div>
                                <div class="text-center">
                                    <div class="badge bg-danger fs-6" id="absents">0</div>
                                    <small class="d-block">Absents</small>
                                </div>
                                <div class="text-center">
                                    <div class="badge bg-warning fs-6" id="retards">0</div>
                                    <small class="d-block">Retards</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tableau des présences -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr class="text-center">
                                <th style="width: 30%">Élève</th>
                                <th style="width: 15%">
                                    <span class="badge bg-success">Présent</span>
                                </th>
                                <th style="width: 15%">
                                    <span class="badge bg-danger">Absent</span>
                                </th>
                                <th style="width: 15%">
                                    <span class="badge bg-warning">Retard</span>
                                </th>
                                <th style="width: 25%">Remarques</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($eleves as $eleve)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle me-3">
                                                {{ substr($eleve->prenom, 0, 1) }}{{ substr($eleve->nom, 0, 1) }}
                                            </div>
                                            <div>
                                                <strong>{{ $eleve->nom }} {{ $eleve->prenom }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $eleve->numero_etudiant ?? 'N/A' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="presences[{{ $eleve->id }}]" value="present"
                                            class="form-check-input presence-radio"
                                            {{ isset($presences[$eleve->id]) && $presences[$eleve->id]->statut == 'present' ? 'checked' : '' }}
                                            {{ !isset($presences[$eleve->id]) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="presences[{{ $eleve->id }}]" value="absent"
                                            class="form-check-input presence-radio"
                                            {{ isset($presences[$eleve->id]) && $presences[$eleve->id]->statut == 'absent' ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="presences[{{ $eleve->id }}]" value="retard"
                                            class="form-check-input presence-radio"
                                            {{ isset($presences[$eleve->id]) && $presences[$eleve->id]->statut == 'retard' ? 'checked' : '' }}>
                                    </td>
                                    <td>
                                        <input type="text" name="remarques[{{ $eleve->id }}]"
                                            value="{{ isset($presences[$eleve->id]) ? $presences[$eleve->id]->remarque : '' }}"
                                            class="form-control form-control-sm" placeholder="Remarque optionnelle">
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">
                                        <i class="fas fa-info-circle me-2"></i>Aucun élève inscrit à ce cours
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($eleves->count() > 0)
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save me-2"></i> Enregistrer les présences
                        </button>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <style>
        .avatar-circle {
            width: 40px;
            height: 40px;
            background-color: #007bff;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 14px;
        }

        .presence-radio {
            transform: scale(1.3);
            cursor: pointer;
        }

        .presence-radio:hover {
            transform: scale(1.4);
        }
    </style>

    <script>
        function changerDate() {
            const dateSelector = document.getElementById('dateSelector');
            const nouvelleDateUrl = "{{ route('enseignant.presences.show', $cours->id) }}/" + dateSelector.value;
            window.location.href = nouvelleDateUrl;
        }

        function marquerTousPresents() {
            document.querySelectorAll('input[value="present"]').forEach(radio => {
                radio.checked = true;
            });
            updateStats();
        }

        function marquerTousAbsents() {
            document.querySelectorAll('input[value="absent"]').forEach(radio => {
                radio.checked = true;
            });
            updateStats();
        }

        function updateStats() {
            const presents = document.querySelectorAll('input[value="present"]:checked').length;
            const absents = document.querySelectorAll('input[value="absent"]:checked').length;
            const retards = document.querySelectorAll('input[value="retard"]:checked').length;

            document.getElementById('presents').textContent = presents;
            document.getElementById('absents').textContent = absents;
            document.getElementById('retards').textContent = retards;
        }

        // Mettre à jour les statistiques en temps réel
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.presence-radio').forEach(radio => {
                radio.addEventListener('change', updateStats);
            });
            updateStats(); // Calculer les stats initiales
        });
    </script>
@endsection
