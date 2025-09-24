@foreach ($niveaux as $niveau)
    @php
        $customBadgeClass = 'badge-niveau-' . (($loop->index % 7) + 1);
        $classes = is_string($niveau->classes_possibles)
            ? json_decode($niveau->classes_possibles, true) ?? []
            : $niveau->classes_possibles ?? [];
    @endphp

    <div class="modal fade" id="editNiveauModal{{ $niveau->id }}" tabindex="-1"
        aria-labelledby="editNiveauLabel{{ $niveau->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('admin.niveaux.update', $niveau->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="modal-header">
                        <h5 class="modal-title" id="editNiveauLabel{{ $niveau->id }}">
                            Modifier le niveau : {{ $niveau->nom }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nom" class="form-label">Nom du niveau</label>
                            <input type="text" name="nom" class="form-control" value="{{ $niveau->nom }}"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Classes possibles</label>
                            <div id="classes-wrapper-{{ $niveau->id }}">
                                @forelse ($classes as $classe)
                                    <div class="input-group mb-2">
                                        <input type="text" name="classes_possibles[]" class="form-control"
                                            value="{{ $classe }}" required>
                                        <button type="button" class="btn btn-danger" onclick="removeField(this)">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                @empty
                                    <div class="input-group mb-2">
                                        <input type="text" name="classes_possibles[]" class="form-control"
                                            placeholder="Ex : 6e A">
                                    </div>
                                @endforelse
                            </div>
                            <button type="button" class="btn btn-outline-secondary btn-sm mt-2"
                                onclick="addClassField('{{ $niveau->id }}')">
                                <i class="fas fa-plus"></i> Ajouter une classe
                            </button>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach
<script>
    function addClassField(niveauId) {
        const wrapper = document.getElementById('classes-wrapper-' + niveauId);
        const div = document.createElement('div');
        div.className = 'input-group mb-2';
        div.innerHTML = `
            <input type="text" name="classes_possibles[]" class="form-control" placeholder="Ex : 6e B" required>
            <button type="button" class="btn btn-danger" onclick="removeField(this)">
                <i class="fas fa-times"></i>
            </button>
        `;
        wrapper.appendChild(div);
    }

    function removeField(button) {
        button.closest('.input-group').remove();
    }
</script>
