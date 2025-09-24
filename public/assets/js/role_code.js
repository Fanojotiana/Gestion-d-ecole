document.addEventListener("DOMContentLoaded", function () {
    const roleSelect = document.querySelector('select[name="role"]');
    const inviteCodeField = document
        .querySelector('input[name="invite_code"]')
        .closest("label");

    function toggleInviteCode() {
        const selectedRole = roleSelect.value;
        if (selectedRole === "enseignant" || selectedRole === "admin") {
            inviteCodeField.style.display = "block";
        } else {
            inviteCodeField.style.display = "none";
            // Optionnel : vider le champ si caché
            inviteCodeField.querySelector('input[name="invite_code"]').value =
                "";
        }
    }

    // Initial call au chargement de la page
    toggleInviteCode();

    // Écouteur de changement sur le select
    roleSelect.addEventListener("change", toggleInviteCode);
});
