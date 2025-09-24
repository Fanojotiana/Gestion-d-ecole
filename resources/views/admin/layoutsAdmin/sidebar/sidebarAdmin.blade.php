<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="menu-title">
                    <span>Menu</span>
                </li>

                <!-- Dashboard -->
                <li>
                    <a href="#">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li><a href="#">Dashboard Admin</a></li>
                        <li><a href="#">Dashboard Enseignant</a></li>
                        <li><a href="#">Dashboard Élève</a></li>
                    </ul>
                </li>

                <!-- Utilisateurs -->
                {{-- <li>
                    <a href="#">
                        <i class="fas fa-shield-alt"></i>
                        <span>Utilisateurs</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li><a href="#">Tous les utilisateurs</a></li>
                    </ul>
                </li> --}}

                <!-- Accès responsable pédagogique -->
                <li>
                    <a href="#">
                        <i class="fas fa-users"></i>
                        <span>Responsables</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li><a href="{{ route('admin.responsables.create') }}">Ajouter un responsable</a></li>
                        <li><a href="{{ route('admin.responsables.index') }}">Liste des responsables</a></li>
                        {{-- <li><a href="#">Suivi des élèves</a></li> --}}
                    </ul>
                </li>
                <!-- Enseignants -->
                <li>
                    <a href="#">
                        <i class="fas fa-chalkboard-teacher"></i>
                        <span>Enseignants</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li><a href="{{ route('admin.enseignants.create') }}">Ajouter un enseignant</a></li>
                        <li><a href="{{ route('admin.enseignants.index') }}">Liste des enseignants</a></li>
                    </ul>
                </li>

                <!-- Élèves -->
                <li>
                    <a href="#">
                        <i class="fas fa-graduation-cap"></i>
                        <span>Élèves</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li><a href="{{ route('admin.eleves.create') }}">Ajouter un élève</a></li>
                        <li><a href="{{ route('admin.eleves.index') }}">Liste des élèves</a></li>
                    </ul>
                </li>

                <!-- Matières -->
                <li>
                    <a href="#">
                        <i class="fas fa-book-reader"></i>
                        <span>Matières</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        {{-- <li><a href="{{route('admin.matieres.create')}}">Ajouter une matière</a></li> --}}
                        <li><a href="{{ route('admin.matieres.index') }}">Ajout et liste des matières</a></li>
                    </ul>
                </li>

                <!-- Classes / Niveaux -->
                <li>
                    <a href="#">
                        <i class="fas fa-layer-group"></i>
                        <span>Classes / Niveaux</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        {{-- <li><a href="{{route('admin.niveaux.create')}}">Ajouter un niveau</a></li> --}}
                        <li><a href="{{ route('admin.niveaux.index') }}">Liste des niveaux</a></li>
                        {{-- <li><a href="{{route('admin.classes.create')}}">Ajouter une classe</a></li> --}}
                        {{-- <li><a href="{{route('admin.classes.index')}}">Liste des classes</a></li> --}}
                    </ul>
                </li>

                <!-- Emplois du temps -->
                <li>
                    <a href="#">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Emplois du temps</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li><a href="{{ route('admin.emplois-du-temps.index') }}">Voir les emplois du temps</a></li>
                        <li><a href="#">Créer un emploi du temps</a></li>
                    </ul>
                </li>

                <!-- Notes -->
                <li>
                    <a href="#">
                        <i class="fas fa-clipboard-check"></i>
                        <span>Notes</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li><a href="{{ route('admin.notes.index') }}">Liste des notes</a></li>
                        <li><a href="{{ route('admin.notes.create') }}">Ajouter une note</a></li>
                        <li><a href="{{ route('admin.rang.eleves') }}"> Rang par classe
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Présence -->
                <li>
                    <a href="#">
                        <i class="fas fa-user-check"></i>
                        <span>Présence</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li><a href="{{ route('admin.presences.index') }}">Liste des présences</a></li>
                        <li><a href="#">Marquer la présence</a></li>
                    </ul>
                </li>

                <!-- Paiement des frais -->
                <li>
                    <a href="#">
                        <i class="fas fa-money-bill-wave"></i>
                        <span>Frais scolaires</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li><a href="#">Paiements</a></li>
                        <li><a href="#">Ajouter un paiement</a></li>
                        <li><a href="#">Paramètres des frais</a></li>
                    </ul>
                </li>


                <!-- Paramètres -->
                <li>
                    <a href="#">
                        <i class="fas fa-cog"></i>
                        <span>Paramètres</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li><a href="#">Paramètres généraux</a></li>
                    </ul>
                </li>
            </ul>

        </div>
    </div>
</div>
