{{-- Formulaire de création d'une classe --}}
<form action="{{ route('admin.classes.store') }}" method="POST">
    @csrf

    <label for="niveau">Niveau</label>
    <select name="niveau_id" id="niveau" required>
        <option value="">-- Choisir un niveau --</option>
        @foreach ($niveaux as $niveau)
            <option value="{{ $niveau->id }}" data-classes='@json($niveau->classes_possibles)'>
                {{ $niveau->nom }}
            </option>
        @endforeach
    </select>

    <label for="classe">Classe</label>
    <select name="nom" id="classe" required>
        <option value="">-- Choisir une classe --</option>
        {{-- Options générées en JS --}}
    </select>

    <button type="submit">Enregistrer</button>
</form>

<script>
    document.getElementById('niveau').addEventListener('change', function() {
        const classesSelect = document.getElementById('classe');
        classesSelect.innerHTML = '<option value="">-- Choisir une classe --</option>'; // reset

        const selectedOption = this.options[this.selectedIndex];
        const classes = selectedOption.dataset.classes ? JSON.parse(selectedOption.dataset.classes) : [];

        classes.forEach(classe => {
            const option = document.createElement('option');
            option.value = classe;
            option.textContent = classe;
            classesSelect.appendChild(option);
        });
    });
</script>
