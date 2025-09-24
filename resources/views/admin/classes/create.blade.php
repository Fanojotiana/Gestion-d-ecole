@extends('admin.layoutsAdmin.dashboardLayoutsAdmin')

@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header mb-4">
                <h3 class="page-title">Ajouter une classe</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Classes</li>
                </ul>
            </div>

            @if (session('success'))
                <script>
                    toastr.success("{{ session('success') }}");
                </script>
            @endif

            <form action="{{ route('admin.classes.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="niveau" class="form-label">Niveau</label>
                    <select name="niveau_id" id="niveau" class="form-select @error('niveau_id') is-invalid @enderror"
                        required>
                        <option value="">-- Choisir un niveau --</option>
                        @foreach ($niveaux as $niveau)
                            <option value="{{ $niveau->id }}" data-classes='@json($niveau->classes_possibles)'
                                {{ old('niveau_id') == $niveau->id ? 'selected' : '' }}>
                                {{ $niveau->nom }}
                            </option>
                        @endforeach
                    </select>
                    @error('niveau_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="classe" class="form-label">Classe</label>
                    <select name="nom" id="classe" class="form-select @error('nom') is-invalid @enderror" required>
                        <option value="">-- Choisir une classe --</option>
                        {{-- options JS --}}
                    </select>
                    @error('nom')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Ajouter</button>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function updateClassesSelect() {
            const niveauSelect = document.getElementById('niveau');
            const classeSelect = document.getElementById('classe');
            classeSelect.innerHTML = '<option value="">-- Choisir une classe --</option>';

            const selectedOption = niveauSelect.options[niveauSelect.selectedIndex];
            if (!selectedOption) return;

            const classes = selectedOption.dataset.classes ? JSON.parse(selectedOption.dataset.classes) : [];

            classes.forEach(classe => {
                const option = document.createElement('option');
                option.value = classe;
                option.textContent = classe;
                if (classe === "{{ old('nom') }}") {
                    option.selected = true;
                }
                classeSelect.appendChild(option);
            });
        }

        document.getElementById('niveau').addEventListener('change', updateClassesSelect);

        // Remplissage au chargement si valeur old existe
        window.onload = updateClassesSelect;
    </script>
@endsection
