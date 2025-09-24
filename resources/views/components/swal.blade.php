@if (session('swal'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: '{{ session('swal.icon', 'success') }}',
                title: '{{ session('swal.title', 'Opération réussie') }}',
                text: '{{ session('swal.text', '') }}',
                confirmButtonText: 'OK',
                timer: 10000,
                timerProgressBar: true,
                allowOutsideClick: false,
                allowEscapeKey: true,
                backdrop: true,
                width: '350px'
            });
        });
    </script>
@endif
