@extends('layouts.auth')
@section('title', 'Connexion')

@section('content')

    @if (session('success'))
        <div class="alert alert-success text-center my-2">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger text-center my-2">
            {{ $errors->first() }}
        </div>
    @endif



    <div class="d-flex align-items-center justify-content-center" style="min-height: 100vh;">
        <div class="login-container">

            {{-- Partie gauche (image + message) --}}
            <div class="login-left" >
                <div style="margin-top: 12rem">
                    <h2 style="color: black">Bienvenue !</h2>
                    <p style="color: rgb(24, 9, 9)">Connectez-vous à votre espace personnel.</p>
                </div>
            </div>

            {{-- Partie droite (formulaire) --}}
            <div class="login-right">
                <div class="text-center mb-3">
                    <img src="{{ asset('assets/img/logo.jpg') }}" alt="Logo" class="logo mb-2">
                    <h3 class="text-primary">Connexion</h3>
                </div>

                <form action="{{ route('login.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <i class="fa fa-envelope form-icon"></i>
                        <input type="email" name="email" class="form-control" placeholder="Adresse email" required>
                    </div>

                    <div class="form-group">
                        <i class="fa fa-lock form-icon"></i>
                        <input id="password" type="password" name="password" class="form-control"
                            placeholder="Mot de passe" required>
                        <span toggle="#password" class="fa fa-fw fa-eye toggle-password"></span>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 btn-login">Se connecter</button>

                    <div class="text-center mt-3">
                        <a href="#" class="text-muted small">Mot de passe oublié ?</a>
                    </div>
                </form>
            </div>

        </div>
    </div>



@endsection
