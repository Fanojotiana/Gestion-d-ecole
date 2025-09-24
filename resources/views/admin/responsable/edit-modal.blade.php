<form action="{{ route('admin.responsables.update', $responsable->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel{{ $responsable->id }}">Modifier Responsable</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
    </div>

    <div class="modal-body">
        <div class="row g-3">
            <div class="col-md-6">
                <label for="nom{{ $responsable->id }}" class="form-label">Nom</label>
                <input type="text" name="nom" id="nom{{ $responsable->id }}" class="form-control"
                    value="{{ old('nom', $responsable->nom) }}" required>
            </div>

            <div class="col-md-6">
                <label for="prenom{{ $responsable->id }}" class="form-label">Prénom</label>
                <input type="text" name="prenom" id="prenom{{ $responsable->id }}" class="form-control"
                    value="{{ old('prenom', $responsable->prenom) }}" required>
            </div>

            <div class="col-md-6">
                <label for="date_naissance{{ $responsable->id }}" class="form-label">Date de naissance</label>
                <input type="date" name="date_naissance" id="date_naissance{{ $responsable->id }}"
                    class="form-control" value="{{ old('date_naissance', $responsable->date_naissance) }}">
            </div>

            <div class="col-md-6">
                <label for="sexe{{ $responsable->id }}" class="form-label">Sexe</label>
                <select name="sexe" id="sexe{{ $responsable->id }}" class="form-select">
                    <option value="" disabled>Choisir</option>
                    <option value="Masculin" {{ old('sexe', $responsable->sexe) === 'Masculin' ? 'selected' : '' }}>
                        Masculin</option>
                    <option value="Féminin" {{ old('sexe', $responsable->sexe) === 'Féminin' ? 'selected' : '' }}>
                        Féminin</option>
                    <option value="Autre" {{ old('sexe', $responsable->sexe) === 'Autre' ? 'selected' : '' }}>Autre
                    </option>
                </select>
            </div>

            <div class="col-md-6">
                <label for="telephone{{ $responsable->id }}" class="form-label">Téléphone</label>
                <input type="text" name="telephone" id="telephone{{ $responsable->id }}" class="form-control"
                    value="{{ old('telephone', $responsable->telephone) }}">
            </div>

            <div class="col-md-6">
                <label for="adresse{{ $responsable->id }}" class="form-label">Adresse</label>
                <input type="text" name="adresse" id="adresse{{ $responsable->id }}" class="form-control"
                    value="{{ old('adresse', $responsable->adresse) }}">
            </div>

            <div class="col-md-6">
                <label for="email{{ $responsable->id }}" class="form-label">Email</label>
                <input type="email" name="email" id="email{{ $responsable->id }}" class="form-control"
                    value="{{ old('email', $responsable->email) }}" required>
            </div>

            <div class="col-md-6">
                <label for="responsable_type{{ $responsable->id }}" class="form-label">Type de responsable</label>
                <select name="responsable_type" id="responsable_type{{ $responsable->id }}" class="form-select">
                    <option value="" disabled>Choisir</option>
                    <option value="type1"
                        {{ old('responsable_type', $responsable->responsable_type) === 'type1' ? 'selected' : '' }}>
                        Type 1</option>
                    <option value="type2"
                        {{ old('responsable_type', $responsable->responsable_type) === 'type2' ? 'selected' : '' }}>
                        Type 2</option>
                    <option value="type3"
                        {{ old('responsable_type', $responsable->responsable_type) === 'type3' ? 'selected' : '' }}>
                        Type 3</option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="password{{ $responsable->id }}" class="form-label">Mot de passe (optionnel)</label>
                <input type="password" name="password" id="password{{ $responsable->id }}" class="form-control">
            </div>

            <div class="col-12">
                <label for="photo{{ $responsable->id }}" class="form-label">Photo</label>
                <input type="file" name="photo" id="photo{{ $responsable->id }}" class="form-control"
                    accept="image/*" onchange="previewPhoto(event, 'preview{{ $responsable->id }}')">
                <img id="preview{{ $responsable->id }}"
                    src="{{ $responsable->photo ? asset('storage/' . $responsable->photo) : asset('assets/img/avatar.jpg') }}"
                    alt="Photo actuelle"
                    style="max-width: 120px; max-height: 120px; object-fit: cover; border-radius: 6px;" class="mt-2">
            </div>


            {{-- <div class="col-md-6">
                <label for="password_confirmation{{ $responsable->id }}" class="form-label">Confirmation mot de
                    passe</label>
                <input type="password" name="password_confirmation" id="password_confirmation{{ $responsable->id }}"
                    class="form-control">
            </div> --}}
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
        <button type="submit" class="btn btn-primary">Mettre à jour</button>
    </div>
</form>

{{-- Script JS pour prévisualisation de la photo --}}
<script>
    function previewPhoto(event, id) {
        const reader = new FileReader();
        reader.onload = function() {
            const output = document.getElementById(id);
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
