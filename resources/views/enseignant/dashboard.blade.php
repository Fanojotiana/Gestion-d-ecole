

@extends('enseignant.layoutsEnseignant.dashboardLayoutsEnseignant')

@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="page-sub-header">
                            <h3 class="page-title">Bienvenue {{ auth()->user()->nom }} !</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard.enseignant') }}">Accueil</a></li>
                                <li class="breadcrumb-item active">Tableau de bord Enseignant</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Widgets de base, tu peux les personnaliser --}}
            <div class="row">
                <div class="col-xl-3 col-sm-6 col-12 d-flex">
                    <div class="card bg-comman w-100">
                        <div class="card-body">
                            <div class="db-widgets d-flex justify-content-between align-items-center">
                                <div class="db-info">
                                    <h6>Mes classes</h6>
                                    <h3>{{ $classesCount ?? 0 }}</h3>
                                </div>
                                <div class="db-icon">
                                    <img src="{{ URL::to('assets/img/icons/dash-icon-03.svg') }}" alt="Classes Icon">
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
                                    <h6>Mes élèves</h6>
                                    <h3>{{ $elevesCount ?? 0 }}</h3>
                                </div>
                                <div class="db-icon">
                                    <img src="{{ URL::to('assets/img/icons/dash-icon-01.svg') }}" alt="Élèves Icon">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Ajoute d’autres widgets si besoin --}}
            </div>

            {{-- Section exemple : Activités ou infos supplémentaires --}}
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card comman-shadow">
                        <div class="card-header">
                            <h5>Dernières activités</h5>
                        </div>
                        <div class="card-body">
                            <p>Pas encore d’activités à afficher.</p>
                            {{-- Tu peux ici afficher les cours, notes, etc. --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
