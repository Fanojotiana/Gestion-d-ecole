@extends('admin.layoutsAdmin.dashboardLayoutsAdmin')

@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="card p-4">
                <h5 class="card-title">Modifier la matière : {{ $matiere->nom }}</h5>

                <form action="{{ route('admin.matieres.update', $matiere) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row gx-3 gy-2">
                        <div class="col-md-6">
                            <label for="nom" class="form-label">Nom de la matière</label>
                            <input type="text" name="nom" class="form-control"
                                value="{{ old('nom', $matiere->nom) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label for="niveau_id" class="form-label">Niveau</label>
                            <select name="niveau_id" class="form-select" required>
                                @foreach ($niveaux as $niveau)
                                    <option value="{{ $niveau->id }}"
                                        {{ $matiere->niveau_id == $niveau->id ? 'selected' : '' }}>
                                        {{ $niveau->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label for="enseignants" class="form-label">Enseignants associés</label>
                            <select name="enseignants[]" class="form-select" multiple>
                                @foreach ($enseignants as $ens)
                                    <option value="{{ $ens->id }}"
                                        {{ $matiere->enseignants->contains($ens->id) ? 'selected' : '' }}>
                                        {{ $ens->nom }} {{ $ens->prenom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-success">Mettre à jour</button>
                        <a href="{{ route('admin.matieres.index') }}" class="btn btn-secondary">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
