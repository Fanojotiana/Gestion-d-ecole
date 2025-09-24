@php
    abort_unless(auth()->user()?->role === 'admin', 403);
@endphp

@extends('admin.layoutsAdmin.dashboardLayoutsAdmin')

@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">

            {{-- Notifications --}}
            @if (session('success'))
                <script>
                    toastr.success("{{ session('success') }}");
                </script>
            @endif
            @if (session('error'))
                <script>
                    toastr.error("{{ session('error') }}");
                </script>
            @endif

            {{-- Formulaire d’ajout --}}
            <div class="card p-4 mb-5">
                <h5 class="card-title">Ajouter un niveau</h5>
                <form action="{{ route('admin.niveaux.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Sélectionner un niveau existant ou en créer un nouveau :</label>
                        <select name="nom_exist" class="form-select mb-2" onchange="disableNewInput(this)">
                            <option value="">-- Aucun niveau existant sélectionné --</option>
                            @foreach ($niveaux as $niv)
                                <option value="{{ $niv->nom }}">{{ $niv->nom }}</option>
                            @endforeach
                        </select>

                        <input type="text" name="nom" id="nom" class="form-control"
                            placeholder="Ou entrer un nouveau nom de niveau" value="{{ old('nom') }}">
                        @error('nom')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Classes à associer</label>
                        <div id="classes-wrapper">
                            <input type="text" name="classes_possibles[]" class="form-control mb-2"
                                placeholder="Ex : 6e A">
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="addClassField()">
                            <i class="fas fa-plus"></i> Ajouter une classe
                        </button>
                        @error('classes_possibles.*')
                            <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>

            {{-- Tableau des niveaux --}}
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                    <h5 class="card-title mb-0">Liste des niveaux</h5>
                    <form action="{{ route('admin.niveaux.index') }}" method="GET" class="d-flex mb-2 mb-md-0">
                        <input type="text" name="search" class="form-control form-control-sm"
                            placeholder="Rechercher..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary btn-sm ms-2">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th style="cursor: pointer;" onclick="sortNiveauxByNom()">
                                        Nom
                                        <span id="sort-icon" class="ms-1"><i class="fas fa-sort"></i></span>
                                    </th>
                                    <th>Classes possibles</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($niveaux as $niveau)
                                    @php
                                        // Définit la classe badge selon l'index du niveau pour le style
$customBadgeClass = 'badge-niveau-' . (($loop->index % 7) + 1);

                                        // Décodage sécurisé des classes possibles
                                        $classes = $niveau->classes_possibles;
                                        if (is_string($classes)) {
                                            $classes = json_decode($classes, true) ?? [];
                                        } elseif (!is_array($classes)) {
                                            $classes = [];
                                        }
                                    @endphp

                                    <tr>
                                        <td>{{ $niveau->id }}</td>

                                        {{-- Nom du niveau --}}
                                        <td>
                                            <span class="badge {{ $customBadgeClass }} fs-6 px-3 py-2">
                                                {{ $niveau->nom }}
                                            </span>
                                        </td>

                                        <td>
                                            @if (count($classes) > 0)
                                                <div class="d-flex flex-wrap gap-1">
                                                    @foreach ($classes as $classe)
                                                        <span class="badge {{ $customBadgeClass }}">
                                                            {{ $classe }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <em>Aucune classe</em>
                                            @endif
                                        </td>

                                        {{-- Actions --}}
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <button type="button"
                                                    class="btn btn-warning btn-sm p-1 d-flex align-items-center justify-content-center"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editNiveauModal{{ $niveau->id }}">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <form action="{{ route('admin.niveaux.destroy', $niveau) }}" method="POST"
                                                    onsubmit="return confirm('Confirmer la suppression ?');" class="m-0">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="btn btn-danger btn-sm p-1 d-flex align-items-center justify-content-center">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">
                                            @if (request('search'))
                                                Aucun niveau trouvé pour « {{ request('search') }} ».
                                            @else
                                                Aucun niveau trouvé.
                                            @endif
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-footer">
                    {{ $niveaux->appends(['search' => request('search')])->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@include('admin.niveaux.edit-modal')

@section('script')
    <script>
        function removeField(button) {
            button.closest('.input-group').remove();
        }

        function addClassField(niveauId = null) {
            const wrapper = niveauId ?
                document.getElementById('classes-wrapper-' + niveauId) :
                document.getElementById('classes-wrapper');

            const div = document.createElement('div');
            div.className = 'input-group mb-2';
            div.innerHTML = `
                <input type="text" name="classes_possibles[]" class="form-control" placeholder="Ex : 6e D">
                <button type="button" class="btn btn-danger" onclick="removeField(this)">
                    <i class="fas fa-times"></i>
                </button>
            `;
            wrapper.appendChild(div);
        }

        function disableNewInput(select) {
            const nomInput = document.getElementById('nom');
            if (select.value) {
                nomInput.disabled = true;
                nomInput.value = '';
            } else {
                nomInput.disabled = false;
            }
        }
    </script>

    {{-- TRI desc et asc --}}
    <script>
        let sortOrderDesc = true;

        const niveauOrdre = {
            '6e': 1,
            '5e': 2,
            '4e': 3,
            '3e': 4,
            '2nde': 5,
            '1re': 6,
            'Terminale': 7
        };

        function cleanText(text) {
            // Supprime les caractères non visibles et blancs
            return text.replace(/\s+/g, ' ').trim();
        }

        function sortNiveauxByNom() {
            const tableBody = document.querySelector("table tbody");
            const rows = Array.from(tableBody.querySelectorAll("tr"));

            rows.sort((a, b) => {
                const aText = cleanText(a.querySelector("td:nth-child(2) .badge")?.innerText || '');
                const bText = cleanText(b.querySelector("td:nth-child(2) .badge")?.innerText || '');

                const aOrder = niveauOrdre[aText] ?? 0;
                const bOrder = niveauOrdre[bText] ?? 0;

                return sortOrderDesc ? bOrder - aOrder : aOrder - bOrder;
            });

            rows.forEach(row => tableBody.appendChild(row));

            sortOrderDesc = !sortOrderDesc;

            // Met à jour l’icône
            const icon = document.getElementById("sort-icon")?.firstElementChild;
            if (icon) {
                icon.className = sortOrderDesc ? "fas fa-sort-down" : "fas fa-sort-up";
            }
        }
    </script>
@endsection
