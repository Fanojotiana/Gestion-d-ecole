<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="menu-title">
                    <span>Tableau de bord</span>
                </li>

                <li>
                    <a href="{{ route('dashboard.enseignant') }}">
                        <i class="fas fa-home"></i>
                        <span>Accueil</span>
                    </a>
                </li>

                <li class="menu-title">
                    <span>Gestion des cours</span>
                </li>

                <li>
                    <a href="{{ route('enseignant.emplois.index') }}">
                        <i class="fas fa-calendar"></i>
                        <span>Emploi du temps</span>
                    </a>
                </li>
                <li>
                    <a href="">
                        <i class="fas fa-book"></i>
                        <span>Mes cours</span>
                    </a>
                </li>

                <li>
                    <a href="">
                        <i class="fas fa-pen"></i>
                        <span>Notes</span>
                    </a>
                </li>

                <!-- Présence -->
                <li>
                    <a href="#">
                        <i class="fas fa-user-check"></i>
                        <span>Présence</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        {{--<li><a href="{{ route('enseignant.presences.index') }}">Gérer les présences</a></li>--}}

                        <li><a href="">Liste des cours</a></li>
                        <li><a href="">Ajout de cours</a></li>
                    </ul>
                </li>

                <li>
                    <a href="">
                        <i class="fas fa-comments"></i>
                        <span>Communication</span>
                    </a>
                </li>

                <li class="menu-title">
                    <span>Mon Compte</span>
                </li>

                <li>
                    <a href="">
                        <i class="fas fa-user"></i>
                        <span>Profil</span>
                    </a>
                </li>

                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="btn w-100 text-start ps-3 py-2 border-0 bg-transparent">
                            <i class="fas fa-sign-out-alt"></i> Déconnexion
                        </button>
                    </form>
                </li>

            </ul>
        </div>
    </div>
</div>
