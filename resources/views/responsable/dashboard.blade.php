@extends('responsable.layoutsResponsable.dashboardLayoutsResponsable')

@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">
            {{-- Header --}}
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="page-sub-header">
                            <h3 class="page-title">Bienvenue {{ Session::get('name') }} !</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('responsable.dashboard') }}">Accueil</a></li>
                                <li class="breadcrumb-item active">Tableau de bord</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Cartes statistiques --}}
            <div class="row">
                <div class="col-xl-3 col-sm-6 col-12 d-flex">
                    <div class="card bg-comman w-100">
                        <div class="card-body">
                            <div class="db-widgets d-flex justify-content-between align-items-center">
                                <div class="db-info">
                                    <h6>Total Paiements</h6>
                                    <h3>1 200 000 Ar</h3>
                                </div>
                                <div class="db-icon">
                                    <img src="{{ asset('assets/img/icons/dash-icon-04.svg') }}" alt="Icon">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-sm-6 col-12 d-flex">
                    <div class="card bg-comman w-100">
                        <div class="card-body">
                            <div class="db-widgets d-flex justify-content-between align-items-center">
                                <div class="db-info">
                                    <h6>Élèves Suivis</h6>
                                    <h3>2</h3>
                                </div>
                                <div class="db-icon">
                                    <img src="{{ asset('assets/img/icons/dash-icon-01.svg') }}" alt="Icon">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-sm-6 col-12 d-flex">
                    <div class="card bg-comman w-100">
                        <div class="card-body">
                            <div class="db-widgets d-flex justify-content-between align-items-center">
                                <div class="db-info">
                                    <h6>Documents</h6>
                                    <h3>5</h3>
                                </div>
                                <div class="db-icon">
                                    <img src="{{ asset('assets/img/icons/dash-icon-03.svg') }}" alt="Icon">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-sm-6 col-12 d-flex">
                    <div class="card bg-comman w-100">
                        <div class="card-body">
                            <div class="db-widgets d-flex justify-content-between align-items-center">
                                <div class="db-info">
                                    <h6>Présences Récentes</h6>
                                    <h3>12/15</h3>
                                </div>
                                <div class="db-icon">
                                    <img src="{{ asset('assets/img/icons/dash-icon-02.svg') }}" alt="Icon">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Graphiques (optionnels si tu veux ajouter avec ApexCharts) --}}
            {{-- Tu peux réutiliser les blocs #bar ou #apexcharts-area comme dans l’admin --}}

            <div class="row">
                <div class="col-md-12">
                    <div class="card card-chart">
                        <div class="card-header">
                            <h5 class="card-title">Évolution des paiements par mois</h5>
                        </div>
                        <div class="card-body">
                            <div id="paiements-chart"></div>
                        </div>
                    </div>
                </div>
            </div>



            {{-- Tableau des paiements récents --}}
            <div class="row">
                <div class="col-xl-12">
                    <div class="card flex-fill">
                        <div class="card-header">
                            <h5 class="card-title">Derniers paiements</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped mb-0">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Montant</th>
                                            <th>Mode</th>
                                            <th>Reçu</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>12 Juin 2025</td>
                                            <td>300 000 Ar</td>
                                            <td>Mobile Money</td>
                                            <td><a href="#" class="btn btn-sm btn-outline-primary">Voir PDF</a></td>
                                        </tr>
                                        <tr>
                                            <td>25 Mai 2025</td>
                                            <td>300 000 Ar</td>
                                            <td>Espèces</td>
                                            <td><a href="#" class="btn btn-sm btn-outline-primary">Voir PDF</a></td>
                                        </tr>
                                        <tr>
                                            <td>30 Avril 2025</td>
                                            <td>300 000 Ar</td>
                                            <td>Mobile Money</td>
                                            <td><a href="#" class="btn btn-sm btn-outline-primary">Voir PDF</a></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
