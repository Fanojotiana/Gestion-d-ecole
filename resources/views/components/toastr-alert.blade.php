<!-- resources/views/components/toastr-alert.blade.php -->
<link href="{{ asset('assets/css/toastr.min.css') }}" rel="stylesheet">

<script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('assets/js/toastr.min.js') }}"></script>


<script>
    toastr.options = {
        // closeButton: true,
        progressBar: true,
        iconClass: '',
        timeOut: 3000,
        positionClass: 'toast-top-right',
        showMethod: 'fadeIn',
        hideMethod: 'fadeOut',
    };

    @if (session('success'))
        toastr.success("{{ session('success') }}");
    @endif
    @if (session('error'))
        toastr.error("{{ session('error') }}");
    @endif

    @if (session('info'))
        toastr.info("{{ session('info') }}");
    @endif

    @if (session('warning'))
        toastr.warning("{{ session('warning') }}");
    @endif
</script>
