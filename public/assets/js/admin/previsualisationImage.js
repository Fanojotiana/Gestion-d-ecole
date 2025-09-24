document.addEventListener("DOMContentLoaded", function () {
    document
        .querySelectorAll('input[type="file"].photo-input')
        .forEach((inputFile) => {
            inputFile.addEventListener("change", function (event) {
                const [file] = this.files;
                if (file) {
                    const previewId = this.getAttribute("data-preview-id");
                    const preview = document.getElementById(previewId);
                    if (preview) {
                        preview.src = URL.createObjectURL(file);
                        preview.onload = () => URL.revokeObjectURL(preview.src);
                    }
                }
            });
        });
});
