@extends('enseignant.layoutsEnseignant.dashboardLayoutsEnseignant')

@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4><i class="fas fa-chart-bar text-primary me-2"></i> Rapport de Présence -
                    {{ $cours->matiere?->nom ?? 'Matière' }}</h4>
                <div>
                    <a href="{{ route('enseignant.presences.index') }}" class="btn btn-secondary me-2">
                        <i class="fas fa-arrow-left me-1"></i> Retour
                    </a>
                    <button onclick="exporterPDF()" class="btn btn-primary">
                        <i class="fas fa-file-pdf me-1"></i> Exporter PDF
                    </button>
                </div>
            </div>

            <!-- Statistiques générales -->
            <div class="row mb-4">
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card bg-primary text-white h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-users fa-2x mb-2"></i>
                            <h3 class="mb-1">{{ count($statistiques) }}</h3>
                            <p class="mb-0">Élèves inscrits</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card bg-success text-white h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-percentage fa-2x mb-2"></i>
                            <h3 class="mb-1">{{ number_format(collect($statistiques)->avg('taux_presence'), 1) }}%</h3>
                            <p class="mb-0">Taux moyen de présence</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card bg-warning text-white h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-clock fa-2x mb-2"></i>
                            <h3 class="mb-1">{{ collect($statistiques)->sum('retards') }}</h3>
                            <p class="mb-0">Total retards</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card bg-danger text-white h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-user-times fa-2x mb-2"></i>
                            <h3 class="mb-1">{{ collect($statistiques)->sum('absents') }}</h3>
                            <p class="mb-0">Total absences</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tableau détaillé -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-table me-2"></i> Détail par élève</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle" id="rapportTable">
                            <thead class="table-light">
                                <tr class="text-center">
                                    <th>Élève</th>
                                    <th>Total cours</th>
                                    <th>Présents</th>
                                    <th>Absents</th>
                                    <th>Retards</th>
                                    <th>Taux présence</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($statistiques as $stat)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle me-3">
                                                    {{ substr($stat['eleve']->prenom, 0, 1) }}{{ substr($stat['eleve']->nom, 0, 1) }}
                                                </div>
                                                <div>
                                                    <strong>{{ $stat['eleve']->nom }} {{ $stat['eleve']->prenom }}</strong>
                                                    <br>
                                                    <small
                                                        class="text-muted">{{ $stat['eleve']->numero_etudiant ?? 'N/A' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-secondary fs-6">{{ $stat['total'] }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-success fs-6">{{ $stat['presents'] }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-danger fs-6">{{ $stat['absents'] }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-warning fs-6">{{ $stat['retards'] }}</span>
                                        </td>
                                        <td class="text-center">
                                            <div class="progress" style="height: 25px; min-width: 100px;">
                                                <div class="progress-bar
                                                @if ($stat['taux_presence'] >= 80) bg-success
                                                @elseif($stat['taux_presence'] >= 60) bg-warning
                                                @else bg-danger @endif"
                                                    role="progressbar" style="width: {{ $stat['taux_presence'] }}%">
                                                    <span class="text-white fw-bold">{{ $stat['taux_presence'] }}%</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            @if ($stat['taux_presence'] >= 80)
                                                <span class="badge bg-success fs-6">
                                                    <i class="fas fa-star me-1"></i>Excellent
                                                </span>
                                            @elseif($stat['taux_presence'] >= 60)
                                                <span class="badge bg-warning fs-6">
                                                    <i class="fas fa-check me-1"></i>Correct
                                                </span>
                                            @elseif($stat['taux_presence'] >= 40)
                                                <span class="badge bg-danger fs-6">
                                                    <i class="fas fa-exclamation me-1"></i>Préoccupant
                                                </span>
                                            @else
                                                <span class="badge bg-dark fs-6">
                                                    <i class="fas fa-times me-1"></i>Critique
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Graphique -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-chart-column me-2"></i> Évolution des présences</h5>
                </div>
                <div class="card-body">
                    <canvas id="presenceChart" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>

    <style>
        .avatar-circle {
            width: 45px;
            height: 45px;
            background-color: #007bff;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 16px;
        }

        .progress {
            position: relative;
        }

        .progress-bar {
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
        }

        .card:hover {
            transform: translateY(-2px);
            transition: transform 0.2s;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Graphique des présences
        const ctx = document.getElementById('presenceChart').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json(collect($statistiques)->pluck('eleve.nom')->take(10)->toArray()),
                datasets: [{
                        label: 'Présents',
                        data: @json(collect($statistiques)->pluck('presents')->take(10)->toArray()),
                        backgroundColor: 'rgba(40, 167, 69, 0.8)',
                        borderColor: 'rgba(40, 167, 69, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Absents',
                        data: @json(collect($statistiques)->pluck('absents')->take(10)->toArray()),
                        backgroundColor: 'rgba(220, 53, 69, 0.8)',
                        borderColor: 'rgba(220, 53, 69, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Retards',
                        data: @json(collect($statistiques)->pluck('retards')->take(10)->toArray()),
                        backgroundColor: 'rgba(255, 193, 7, 0.8)',
                        borderColor: 'rgba(255, 193, 7, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Statistiques de présence par élève'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });

        function exporterPDF() {
            // Implémenter l'export PDF
            alert('Fonctionnalité d\'export PDF à implémenter');
        }
    </script>
@endsection
