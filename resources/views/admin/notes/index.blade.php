@extends('admin.layoutsAdmin.dashboardLayoutsAdmin')

@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">

            {{-- Titre et bouton --}}
            <div class="card shadow mb-4 border-0">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0"><i class="fas fa-clipboard me-2 text-secondary"></i> Liste des notes</h5>
                    <a href="{{ route('admin.notes.create') }}" class="btn btn-sm btn-success">
                        <i class="fas fa-plus"></i> Ajouter une note
                    </a>
                </div>
            </div>

            {{-- Alertes --}}
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

            {{-- Tableau --}}
            <div class="card shadow border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        {{-- Filtres --}}
                        <div class="card mb-4 shadow-sm border-0">
                            <div class="card-body">
                                <form method="GET" action="{{ route('admin.notes.index') }}"
                                    class="row gy-2 gx-3 align-items-end">
                                    {{-- Classe --}}
                                    <div class="col-12 col-md-4">
                                        <label for="classe_id" class="form-label fw-semibold">Classe</label>
                                        <select name="classe_id" id="classe_id" class="form-select"
                                            onchange="this.form.submit()">
                                            <option value="">-- Toutes les classes --</option>
                                            @foreach ($classes as $classe)
                                                <option value="{{ $classe->id }}"
                                                    {{ isset($classeId) && $classeId == $classe->id ? 'selected' : '' }}>
                                                    {{ $classe->nom }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- Matière --}}
                                    <div class="col-12 col-md-4">
                                        <label for="matiere_id" class="form-label fw-semibold">Matière</label>
                                        <select name="matiere_id" id="matiere_id" class="form-select"
                                            onchange="this.form.submit()">
                                            <option value="">-- Toutes les matières --</option>
                                            @foreach ($matieres as $matiere)
                                                <option value="{{ $matiere->id }}"
                                                    {{ isset($matiereId) && $matiereId == $matiere->id ? 'selected' : '' }}>
                                                    {{ $matiere->nom }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- Période --}}
                                    <div class="col-12 col-md-3">
                                        <label for="periode" class="form-label fw-semibold">Période</label>
                                        <select name="periode" id="periode" class="form-select"
                                            onchange="this.form.submit()">
                                            <option value="">-- Toutes les périodes --</option>
                                            <option value="Trimestre 1"
                                                {{ isset($periode) && $periode == 'Trimestre 1' ? 'selected' : '' }}>
                                                Trimestre 1</option>
                                            <option value="Trimestre 2"
                                                {{ isset($periode) && $periode == 'Trimestre 2' ? 'selected' : '' }}>
                                                Trimestre 2</option>
                                            <option value="Trimestre 3"
                                                {{ isset($periode) && $periode == 'Trimestre 3' ? 'selected' : '' }}>
                                                Trimestre 3</option>
                                        </select>
                                    </div>

                                    {{-- Bouton de réinitialisation --}}
                                    @if ($classeId || $matiereId || $periode)
                                        <div class="col-12 col-md-1 text-end">
                                            <a href="{{ route('admin.notes.index') }}" class="btn btn-outline-secondary"
                                                title="Réinitialiser les filtres">
                                                <i class="fas fa-redo-alt"></i>
                                            </a>
                                        </div>
                                    @endif
                                </form>
                            </div>
                        </div>

                        {{-- Tableau des notes --}}
                        <table class="table table-bordered table-hover align-middle mb-0 text-center">
                            <thead class="table-light">
                                <tr>
                                    <th>Élève</th>
                                    @foreach ($matieres as $matiere)
                                        <th>{{ $matiere->nom }}</th>
                                    @endforeach
                                    <th>Actions</th> {{-- Colonne Actions globale --}}
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($groupedNotes as $eleveId => $eleveNotes)
                                    @php $eleve = $eleveNotes->first()->eleve; @endphp
                                    <tr>
                                        <td>{{ $eleve->nom }} {{ $eleve->prenom }}</td>
                                        @foreach ($matieres as $matiere)
                                            @php
                                                $note = $eleveNotes->firstWhere('matiere_id', $matiere->id);
                                                $noteValue = $note ? $note->note : null;
                                                if (is_null($noteValue)) {
                                                    $noteClass = '';
                                                } elseif ($noteValue < 10) {
                                                    $noteClass = 'text-danger';
                                                } elseif ($noteValue < 15) {
                                                    $noteClass = 'text-warning';
                                                } else {
                                                    $noteClass = 'text-success';
                                                }
                                            @endphp
                                            <td>
                                                @if ($note)
                                                    <strong
                                                        class="{{ $noteClass }}">{{ $noteValue }}/20</strong><br>
                                                    Coef: {{ $note->coefficient }}<br>
                                                    {{ $note->periode }}<br>
                                                    {{ $note->commentaire ?? '-' }}
                                                @else
                                                    <span class="text-muted">–</span>
                                                @endif
                                            </td>
                                        @endforeach
                                        <td>
                                            {{-- Bouton Voir détails --}}
                                            <button type="button" class="btn btn-sm btn-info" title="Voir détails"
                                                data-bs-toggle="modal" data-bs-target="#showNotesModal"
                                                data-notes='@json($eleveNotes)'>
                                                <i class="fas fa-eye"></i>
                                            </button>

                                            {{-- Bouton Modifier avec modale --}}
                                            <button type="button" class="btn btn-sm btn-warning" title="Modifier"
                                                data-bs-toggle="modal" data-bs-target="#editNotesModal"
                                                data-notes='@json($eleveNotes)'>
                                                <i class="fas fa-edit"></i>
                                            </button>

                                            {{-- Bouton Supprimer --}}
                                            <form action="{{ route('admin.notes.destroy', $eleve->id) }}" method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Supprimer toutes les notes de cet élève ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger" title="Supprimer">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ 2 + count($matieres) }}" class="text-muted">Aucune note
                                            enregistrée.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="modal fade" id="editNotesModal" tabindex="-1" aria-labelledby="editNotesModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                <form id="editNotesForm" method="POST" action="">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editNotesModalLabel">Modifier les notes</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Fermer"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div id="notesFieldsContainer">
                                                {{-- Les inputs seront injectés ici par JS --}}
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Annuler</button>
                                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>

                    {{-- Pagination --}}
                    <div class="p-3">
                        {{-- {{ $notes->links() }} --}}
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal d'édition --}}
        <div class="modal fade" id="editNoteModal" tabindex="-1" aria-labelledby="editNoteModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <form id="editNoteForm" method="POST" action="">
                    @csrf
                    @method('PUT')
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editNoteModalLabel">Modifier la note</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Fermer"></button>
                        </div>
                        <div class="modal-body">
                            {{-- Champs du formulaire d'édition --}}
                            <input type="hidden" name="note_id" id="edit_note_id">

                            <div class="mb-3">
                                <label for="edit_classe_id" class="form-label">Classe</label>
                                <select name="classe_id" id="edit_classe_id" class="form-select"
                                    onchange="getElevesEdit(this.value)" required>
                                    <option value="">-- Sélectionner une classe --</option>
                                    @foreach ($classes as $classe)
                                        <option value="{{ $classe->id }}">{{ $classe->nom }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="edit_eleve_id" class="form-label">Élève</label>
                                <select name="eleve_id" id="edit_eleve_id" class="form-select" required>
                                    <option value="">-- Sélectionner un élève --</option>
                                    {{-- Rempli dynamiquement --}}
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="edit_matiere_id" class="form-label">Matière</label>
                                <select name="matiere_id" id="edit_matiere_id" class="form-select" required>
                                    <option value="">-- Sélectionner une matière --</option>
                                    @foreach ($matieres as $matiere)
                                        <option value="{{ $matiere->id }}">{{ $matiere->nom }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="edit_note" class="form-label">Note (/20)</label>
                                <input type="number" step="0.01" max="20" name="note" id="edit_note"
                                    class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="edit_coefficient" class="form-label">Coefficient</label>
                                <input type="number" step="0.1" name="coefficient" id="edit_coefficient"
                                    class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="edit_periode" class="form-label">Période</label>
                                <select name="periode" id="edit_periode" class="form-select" required>
                                    <option value="">-- Choisir --</option>
                                    <option value="Trimestre 1">Trimestre 1</option>
                                    <option value="Trimestre 2">Trimestre 2</option>
                                    <option value="Trimestre 3">Trimestre 3</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="edit_commentaire" class="form-label">Commentaire (facultatif)</label>
                                <textarea name="commentaire" id="edit_commentaire" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="showNotesModal" tabindex="-1" aria-labelledby="showNotesModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="showNotesModalLabel">Détails des notes de l'élève</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Matière</th>
                                <th>Note (/20)</th>
                                <th>Coefficient</th>
                                <th>Total pondéré</th>
                            </tr>
                        </thead>
                        <tbody id="showNotesTableBody">
                            {{-- Contenu rempli par JS --}}
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-end">Somme coefficients :</th>
                                <th id="sumCoefficients">0</th>
                            </tr>
                            <tr>
                                <th colspan="3" class="text-end">Total pondéré :</th>
                                <th id="totalPondere">0</th>
                            </tr>
                            <tr>
                                <th colspan="3" class="text-end">Moyenne pondérée :</th>
                                <th id="moyennePonderee">0</th>
                            </tr>
                            <tr>
                                <th colspan="3" class="text-end">Observation :</th>
                                <th id="observationText">-</th>
                            </tr>

                        </tfoot>
                    </table>

                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-primary">
                        Exporter PDF (Trimestre 1)
                    </a>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>

@endsection


@section('script')
    <script>
        // Charger dynamiquement les élèves dans le formulaire d'édition
        function getElevesEdit(classeId) {
            const eleveSelect = document.getElementById('edit_eleve_id');
            eleveSelect.innerHTML = '<option>Chargement...</option>';
            if (!classeId) {
                eleveSelect.innerHTML = '<option value="">-- Sélectionner un élève --</option>';
                return;
            }

            fetch(`/admin/classes/${classeId}/eleves`)
                .then(res => res.ok ? res.json() : Promise.reject())
                .then(data => {
                    eleveSelect.innerHTML = '<option value="">-- Sélectionner un élève --</option>';
                    data.forEach(eleve => {
                        eleveSelect.innerHTML +=
                            `<option value="${eleve.id}">${eleve.nom} ${eleve.prenom}</option>`;
                    });
                })
                .catch(() => {
                    eleveSelect.innerHTML = '<option value="">Erreur lors du chargement</option>';
                });
        }

        // Pré-remplir la modale avec les données de la note cliquée
        const editNoteModal = document.getElementById('editNoteModal');
        editNoteModal.addEventListener('show.bs.modal', event => {
            const button = event.relatedTarget;
            const note = JSON.parse(button.getAttribute('data-note'));

            // Changer l'URL du formulaire PUT vers la bonne route update
            const form = document.getElementById('editNoteForm');
            form.action = `/admin/notes/${note.id}`;

            // Remplir les champs
            document.getElementById('edit_note_id').value = note.id;
            document.getElementById('edit_classe_id').value = note.eleve.classe_id;

            // Charger les élèves de la classe sélectionnée, puis sélectionner l'élève
            getElevesEdit(note.eleve.classe_id);
            // On attend un peu que les options soient chargées, puis on sélectionne l'élève
            setTimeout(() => {
                document.getElementById('edit_eleve_id').value = note.eleve_id;
            }, 300);

            document.getElementById('edit_matiere_id').value = note.matiere_id;
            document.getElementById('edit_note').value = note.note;
            document.getElementById('edit_coefficient').value = note.coefficient;
            document.getElementById('edit_periode').value = note.periode;
            document.getElementById('edit_commentaire').value = note.commentaire ?? '';
        });
    </script>

    <script>
        // Déclare cette constante dans ta vue Blade, en générant l'URL dynamique avec Blade
        const updateMultipleRouteTemplate = "{{ url('admin/notes/update-multiple') }}/:eleveId";

        const editNotesModal = document.getElementById('editNotesModal');
        const notesFieldsContainer = document.getElementById('notesFieldsContainer');
        const editNotesForm = document.getElementById('editNotesForm');

        editNotesModal.addEventListener('show.bs.modal', event => {
            const button = event.relatedTarget;
            const notes = JSON.parse(button.getAttribute('data-notes'));

            // Nettoyer container
            notesFieldsContainer.innerHTML = '';

            if (notes.length === 0) {
                notesFieldsContainer.innerHTML = '<p>Aucune note à modifier.</p>';
                return;
            }

            // Modifier l'action du formulaire (adapter à ta route)
            const eleveId = notes[0].eleve_id;
            editNotesForm.action = updateMultipleRouteTemplate.replace(':eleveId', eleveId);

            // Pour chaque note, on crée un champ de formulaire
            notes.forEach(note => {
                const div = document.createElement('div');
                div.classList.add('mb-3', 'border-bottom', 'pb-3');

                div.innerHTML = `
                <input type="hidden" name="notes[${note.id}][id]" value="${note.id}">
                <div><strong>Matière :</strong> ${note.matiere.nom}</div>

                <label for="note_${note.id}" class="form-label">Note (/20)</label>
                <input type="number" step="0.01" max="20" name="notes[${note.id}][note]" id="note_${note.id}" class="form-control" value="${note.note}" required>

                <label for="coef_${note.id}" class="form-label mt-2">Coefficient</label>
                <input type="number" step="0.1" name="notes[${note.id}][coefficient]" id="coef_${note.id}" class="form-control" value="${note.coefficient}" required>

                <label for="periode_${note.id}" class="form-label mt-2">Période</label>
                <select name="notes[${note.id}][periode]" id="periode_${note.id}" class="form-select" required>
                    <option value="Trimestre 1" ${note.periode === 'Trimestre 1' ? 'selected' : ''}>Trimestre 1</option>
                    <option value="Trimestre 2" ${note.periode === 'Trimestre 2' ? 'selected' : ''}>Trimestre 2</option>
                    <option value="Trimestre 3" ${note.periode === 'Trimestre 3' ? 'selected' : ''}>Trimestre 3</option>
                </select>

                <label for="commentaire_${note.id}" class="form-label mt-2">Commentaire</label>
                <textarea name="notes[${note.id}][commentaire]" id="commentaire_${note.id}" class="form-control">${note.commentaire ? note.commentaire : ''}</textarea>
            `;

                notesFieldsContainer.appendChild(div);
            });
        });
    </script>

    {{-- Modale show details --}}
    <script>
        const showNotesModal = document.getElementById('showNotesModal');
        const showNotesTableBody = document.getElementById('showNotesTableBody');
        const sumCoefficientsElem = document.getElementById('sumCoefficients');
        const totalPondereElem = document.getElementById('totalPondere');
        const moyennePondereeElem = document.getElementById('moyennePonderee');

        showNotesModal.addEventListener('show.bs.modal', event => {
            const button = event.relatedTarget;
            const notes = JSON.parse(button.getAttribute('data-notes'));

            showNotesTableBody.innerHTML = '';

            let sumCoefficients = 0;
            let totalPondere = 0;

            notes.forEach(note => {
                const coef = parseFloat(note.coefficient) || 0;
                const valNote = parseFloat(note.note) || 0;
                const total = valNote * coef;
                sumCoefficients += coef;
                totalPondere += total;

                const tr = document.createElement('tr');
                tr.innerHTML = `
    <td>${note.matiere.nom}</td>
    <td>${valNote.toFixed(2)}</td>
    <td>${coef.toFixed(2)}</td>
    <td>${total.toFixed(2)}</td>
    `;
                showNotesTableBody.appendChild(tr);
            });

            sumCoefficientsElem.textContent = sumCoefficients.toFixed(2);
            totalPondereElem.textContent = totalPondere.toFixed(2);

            const moyenne = sumCoefficients > 0 ? (totalPondere / sumCoefficients) : 0;
            moyennePondereeElem.textContent = moyenne.toFixed(2);

            // Observation selon la moyenne
            let observation = '-';
            if (moyenne < 10) {
                observation = 'Insuffisant';
            } else if (moyenne < 15) {
                observation = 'Passable';
            } else {
                observation = 'Très bien';
            }
            document.getElementById('observationText').textContent = observation;
        });
    </script>

    {{-- BULLETION TRIMESTRE --}}
    <script>
        const showNotesModal = document.getElementById('showNotesModal');
        const exportPdfBtn = document.getElementById('exportPdfBtn');

        showNotesModal.addEventListener('show.bs.modal', event => {
            const button = event.relatedTarget;
            const notes = JSON.parse(button.getAttribute('data-notes'));

            if (notes.length === 0) {
                exportPdfBtn.href = '#';
                exportPdfBtn.classList.add('disabled');
                return;
            }

            const eleveId = notes[0].eleve_id;
            exportPdfBtn.classList.remove('disabled');

            // Construire URL vers la route d'export PDF (à adapter selon ta route)
            // Par exemple : /admin/notes/pdf/{eleve}/{periode}
            exportPdfBtn.href = `/notes/pdf/${eleveId}/Trimestre%201`;
        });
    </script>
@endsection
