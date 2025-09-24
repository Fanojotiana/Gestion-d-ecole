<form action="{{ route('admin.enseignants.update', $enseignant->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel{{ $enseignant->id }}">Modifier Enseignant</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
    </div>
    <input type="hidden" name="user_id" value="{{ old('user_id', $enseignant->user_id) }}">

    <div class="modal-body">
        <div class="row g-3">
            <div class="col-md-6">
                <label for="nom{{ $enseignant->id }}" class="form-label">Nom</label>
                <input type="text" name="nom" id="nom{{ $enseignant->id }}" class="form-control"
                    value="{{ old('nom', $enseignant->nom) }}" required>
            </div>

            <div class="col-md-6">
                <label for="prenom{{ $enseignant->id }}" class="form-label">Prénom</label>
                <input type="text" name="prenom" id="prenom{{ $enseignant->id }}" class="form-control"
                    value="{{ old('prenom', $enseignant->prenom) }}" required>
            </div>

            <div class="col-md-6">
                <label for="matricule{{ $enseignant->id }}" class="form-label">Matricule</label>
                <input type="text" name="matricule" id="matricule{{ $enseignant->id }}" class="form-control"
                    value="{{ old('matricule', $enseignant->matricule) }}" required>
            </div>

            {{-- Champ matières multiples --}}
            <div class="col-md-6">
                <label for="matieres{{ $enseignant->id }}" class="form-label">Matières</label>
                <select name="matieres[]" id="matieres{{ $enseignant->id }}" class="form-select" multiple required>
                    @foreach ($allMatieres as $matiere)
                        <option value="{{ $matiere->id }}"
                            {{ in_array($matiere->id, old('matieres', $enseignant->matieres->pluck('id')->toArray())) ? 'selected' : '' }}>
                            {{ $matiere->nom }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label for="grade{{ $enseignant->id }}" class="form-label">Grade</label>
                <input type="text" name="grade" id="grade{{ $enseignant->id }}" class="form-control"
                    value="{{ old('grade', $enseignant->grade) }}">
            </div>

            <div class="col-md-6">
                <label for="email{{ $enseignant->id }}" class="form-label">Email</label>
                <input type="email" name="email" id="email{{ $enseignant->id }}" class="form-control"
                    value="{{ old('email', $enseignant->email) }}" required>
            </div>

            <div class="col-md-6">
                <label for="telephone{{ $enseignant->id }}" class="form-label">Téléphone</label>
                <input type="tel" name="telephone" id="telephone{{ $enseignant->id }}" class="form-control"
                    value="{{ old('telephone', $enseignant->telephone) }}" required>
            </div>

            <div class="col-md-6">
                <label for="adresse{{ $enseignant->id }}" class="form-label">Adresse</label>
                <input type="text" name="adresse" id="adresse{{ $enseignant->id }}" class="form-control"
                    value="{{ old('adresse', $enseignant->adresse) }}">
            </div>

            <div class="col-md-12 mt-3">
                <label for="photo{{ $enseignant->id }}" class="form-label">Photo</label>
                <input type="file" name="photo" id="photo{{ $enseignant->id }}" class="form-control photo-input"
                    accept="image/*" data-preview-id="previewPhoto{{ $enseignant->id }}">

                <img id="previewPhoto{{ $enseignant->id }}"
                    src="{{ $enseignant->photo ? asset('storage/' . $enseignant->photo) : asset('assets/img/avatar.jpg') }}"
                    alt="Photo actuelle"
                    style="max-width: 150px; max-height: 150px; object-fit: cover; border-radius: 8px; margin-top: 10px;">
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
        <button type="submit" class="btn btn-primary">Mettre à jour</button>
    </div>
</form>
