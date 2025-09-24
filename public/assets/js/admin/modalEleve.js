function loadEditModal(eleveId) {
    fetch(`/admin/eleves/${eleveId}/edit`)
        .then((response) => response.text())
        .then((html) => {
            document.getElementById("edit-eleve-content").innerHTML = html;
        });
}
