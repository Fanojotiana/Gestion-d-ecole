<div class="modal-body">
    <form action="{{ route('admin.eleves.update', $eleve) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="modal-header">
            <h5 class="modal-title" id="editModalLabel{{ $eleve->id }}">Modifier Élève</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
        </div>

        <div class="modal-body">
            <div class="row g-3">
                {{-- Nom, prénom, matricule, genre --}}
                <div class="col-md-6">
                    <label class="form-label">Nom</label>
                    <input type="text" name="nom" class="form-control" value="{{ old('nom', $eleve->nom) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Prénom</label>
                    <input type="text" name="prenom" class="form-control" value="{{ old('prenom', $eleve->prenom) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Matricule</label>
                    <input type="text" name="matricule" class="form-control" value="{{ old('matricule', $eleve->matricule) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Genre</label>
                    <select name="genre" class="form-control" required>
                        <option value="">-- Choisir --</option>
                        <option value="M" {{ $eleve->genre === 'M' ? 'selected' : '' }}>Masculin</option>
                        <option value="F" {{ $eleve->genre === 'F' ? 'selected' : '' }}>Féminin</option>
                    </select>
                </div>

                {{-- Date naissance, urgence, adresse --}}
                <div class="col-md-6">
                    <label class="form-label">Date de naissance</label>
                    <input type="date" name="date_naissance" class="form-control" value="{{ old('date_naissance', $eleve->date_naissance) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Contact d'urgence</label>
                    <input type="text" name="urgence_contact" class="form-control" value="{{ old('urgence_contact', $eleve->urgence_contact) }}">
                </div>
                <div class="col-md-12">
                    <label class="form-label">Adresse</label>
                    <input type="text" name="adresse" class="form-control" value="{{ old('adresse', $eleve->adresse) }}">
                </div>

                {{-- Niveau et classe --}}
                <div class="col-md-6">
                    <label class="form-label">Niveau</label>
                    <select name="niveau_id" class="form-control" required>
                        <option value="">-- Choisir un niveau --</option>
                        @foreach ($niveaux as $niveau)
                            <option value="{{ $niveau->id }}"
                                {{ $eleve->niveau_id == $niveau->id ? 'selected' : '' }}>
                                {{ $niveau->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Classe</label>
                    <select name="classe_id" class="form-control" required>
                        <option value="">-- Choisir une classe --</option>
                        @foreach ($classes as $classe)
                            <option value="{{ $classe->id }}"
                                {{ $eleve->classe_id == $classe->id ? 'selected' : '' }}>
                                {{ $classe->nom }} - {{ $classe->niveau->nom ?? 'Niveau inconnu' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Photo --}}
                <div class="col-md-12">
                    <label class="form-label">Photo</label>
                    <input type="file" name="photo" class="form-control photo-input"
                        accept="image/*" data-preview-id="previewPhoto{{ $eleve->id }}">
                </div>
                <div class="col-md-12 text-center mt-2">
                    <img id="previewPhoto{{ $eleve->id }}"
                        src="{{ $eleve->photo ? asset('storage/' . $eleve->photo) : asset('assets/img/avatar.jpg') }}"
                        alt="Photo actuelle"
                        style="max-width: 120px; max-height: 120px; object-fit: cover; border-radius: 8px;">
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
            <button type="submit" class="btn btn-primary">Mettre à jour</button>
        </div>
    </form>
</div>
