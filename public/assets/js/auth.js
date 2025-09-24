document.addEventListener("DOMContentLoaded", function () {
    const togglePassword = document.querySelector(".toggle-password");
    const passwordInput = document.querySelector(
        togglePassword.getAttribute("toggle")
    );

    // Initialiser l'icône selon le type initial du champ
    if (passwordInput.type === "password") {
        togglePassword.classList.remove("fa-eye");
        togglePassword.classList.add("fa-eye-slash");
    } else {
        togglePassword.classList.remove("fa-eye-slash");
        togglePassword.classList.add("fa-eye");
    }

    togglePassword.addEventListener("click", function () {
        const type =
            passwordInput.getAttribute("type") === "password"
                ? "text"
                : "password";
        passwordInput.setAttribute("type", type);

        // Inverse la logique des classes ici
        if (type === "password") {
            this.classList.remove("fa-eye");
            this.classList.add("fa-eye-slash");
        } else {
            this.classList.remove("fa-eye-slash");
            this.classList.add("fa-eye");
        }
    });
});
