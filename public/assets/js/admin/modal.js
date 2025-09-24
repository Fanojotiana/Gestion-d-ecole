function showDetails(enseignant) {
    const modalContent = `
        <div class="row">
            <div class="col-md-4 text-center mb-4">
                <img src="/storage/${enseignant.photo ?? "images/default.jpg"}"
                     class="img-fluid rounded-circle border border-3 border-primary shadow-sm"
                     style="width: 140px; height: 140px; object-fit: cover;">
            </div>
            <div class="col-md-8">
                <ul class="list-group list-group-flush fs-6">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="fw-semibold text-primary">Nom :</span>
                        <span>${enseignant.nom}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="fw-semibold text-primary">Prénom :</span>
                        <span>${enseignant.prenom}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="fw-semibold text-primary">Adresse :</span>
                        <span>${enseignant.adresse || "N/A"}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="fw-semibold text-primary">Matricule :</span>
                        <span>${enseignant.matricule || "N/A"}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="fw-semibold text-primary">Spécialité :</span>
                        <span>${enseignant.specialite}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="fw-semibold text-primary">Grade :</span>
                        <span>${enseignant.grade || "N/A"}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="fw-semibold text-primary">Email :</span>
                        <span><a href="mailto:${
                            enseignant.email
                        }" class="text-decoration-none">${
        enseignant.email
    }</a></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="fw-semibold text-primary">Téléphone :</span>
                        <span><a href="tel:${
                            enseignant.telephone
                        }" class="text-decoration-none">${
        enseignant.telephone
    }</a></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="fw-semibold text-primary">Date d'ajout :</span>
                        <span>${new Date(
                            enseignant.created_at
                        ).toLocaleDateString()}</span>
                    </li>
                </ul>
            </div>
        </div>
    `;
    document.getElementById("modal-content").innerHTML = modalContent;
}
