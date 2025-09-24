function showDetails(eleve) {
    const modalContent = `
        <div class="row">
            <div class="col-md-4 text-center mb-4">
                <img src="/storage/${eleve.photo ?? "images/default.jpg"}"
                     class="img-fluid rounded-circle border border-3 border-primary shadow-sm"
                     style="width: 140px; height: 140px; object-fit: cover;">
            </div>
            <div class="col-md-8">
                <ul class="list-group list-group-flush fs-6">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="fw-semibold text-primary">Nom :</span>
                        <span>${eleve.nom ?? "N/A"}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="fw-semibold text-primary">Prénom :</span>
                        <span>${eleve.prenom ?? "N/A"}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="fw-semibold text-primary">Matricule :</span>
                        <span>${eleve.matricule ?? "N/A"}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="fw-semibold text-primary">Genre :</span>
                        <span>${
                            eleve.genre === "M" ? "Masculin" : "Féminin"
                        }</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="fw-semibold text-primary">Classe :</span>
                        <span>${eleve.classe?.nom ?? "Non défini"}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="fw-semibold text-primary">Adresse :</span>
                        <span>${eleve.adresse ?? "N/A"}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="fw-semibold text-primary">Date de naissance :</span>
                        <span>${
                            eleve.date_naissance
                                ? new Date(
                                      eleve.date_naissance
                                  ).toLocaleDateString("fr-FR")
                                : "N/A"
                        }</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="fw-semibold text-primary">Contact d'urgence :</span>
                        <span>${eleve.urgence_contact ?? "N/A"}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="fw-semibold text-primary">Date d’ajout :</span>
                        <span>${new Date(eleve.created_at).toLocaleDateString(
                            "fr-FR"
                        )}</span>
                    </li>
                </ul>
            </div>
        </div>
    `;
    document.getElementById("modal-content").innerHTML = modalContent;
}
