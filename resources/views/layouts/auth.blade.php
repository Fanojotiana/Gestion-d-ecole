<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="{{asset('assets/bootstrap/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/css/auth.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/fontawesome/css/all.min.css') }}" />

    <title>Authentification | @yield('title')</title>
</head>

<body>
    <video autoplay muted loop playsinline>
        <source src="{{ asset('assets/video/backgroun_videoAuth.mp4') }}" type="video/mp4">
        Votre navigateur ne supporte pas la vidéo.
    </video>
    <main>
        @yield('content')
    </main>

    <script src="{{ asset('assets/js/script.js') }}"></script>
    {{-- <script src="{{asset('assets/js/role_code.js')}}"></script> --}}
    <script src="{{asset('assets/js/auth.js')}}"></script>

</body>

</html>
