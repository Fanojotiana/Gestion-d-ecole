@extends('layouts.auth')
@section('title', 'Connexion')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900 flex items-center justify-center p-4">
    <div class="w-full max-w-6xl mx-auto">

        {{-- Messages de session --}}
        @if (session('success'))
            <div class="mb-6 p-4 bg-green-500/10 border border-green-500/20 rounded-lg text-green-400 text-center backdrop-blur-sm">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-500/10 border border-red-500/20 rounded-lg text-red-400 text-center backdrop-blur-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="bg-white/5 backdrop-blur-xl rounded-2xl shadow-2xl border border-white/10 overflow-hidden">
            <div class="grid lg:grid-cols-2 min-h-[600px]">

                {{-- Partie gauche (message de bienvenue) --}}
                <div class="relative bg-gradient-to-br from-purple-600 to-blue-600 p-12 flex items-center justify-center">
                    <div class="absolute inset-0 bg-black/20"></div>
                    <div class="relative z-10 text-center text-white">
                        <div class="mb-8">
                            <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-6 backdrop-blur-sm">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                        </div>
                        <h2 class="text-4xl font-bold mb-4 text-balance">Bienvenue !</h2>
                        <p class="text-xl text-white/80 text-pretty">Connectez-vous à votre espace personnel et accédez à toutes vos fonctionnalités.</p>
                        <div class="mt-8 flex justify-center space-x-2">
                            <div class="w-2 h-2 bg-white/40 rounded-full"></div>
                            <div class="w-2 h-2 bg-white/60 rounded-full"></div>
                            <div class="w-2 h-2 bg-white rounded-full"></div>
                        </div>
                    </div>
                </div>

                {{-- Partie droite (formulaire) --}}
                <div class="p-12 flex items-center justify-center bg-white/5">
                    <div class="w-full max-w-md">

                        {{-- Header du formulaire --}}
                        <div class="text-center mb-8">
                            <div class="mb-6">
                                <img src="{{ asset('assets/img/logo.jpg') }}" alt="Logo" class="w-16 h-16 mx-auto rounded-xl shadow-lg object-cover">
                            </div>
                            <h3 class="text-2xl font-bold text-white mb-2">Connexion</h3>
                            <p class="text-gray-400">Accédez à votre compte</p>
                        </div>

                        {{-- Formulaire --}}
                        <form action="{{ route('login.store') }}" method="POST" class="space-y-6">
                            @csrf

                            {{-- Champ email --}}
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                                    </svg>
                                </div>
                                <input
                                    type="email"
                                    name="email"
                                    class="w-full pl-12 pr-4 py-4 bg-white/10 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200 backdrop-blur-sm"
                                    placeholder="Adresse email"
                                    required
                                    value="{{ old('email') }}"
                                >
                            </div>

                            {{-- Champ mot de passe --}}
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                </div>
                                <input
                                    id="password"
                                    type="password"
                                    name="password"
                                    class="w-full pl-12 pr-12 py-4 bg-white/10 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200 backdrop-blur-sm"
                                    placeholder="Mot de passe"
                                    required
                                >
                                <button
                                    type="button"
                                    class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-white transition-colors duration-200"
                                    onclick="togglePassword()"
                                >
                                    <svg id="eye-open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    <svg id="eye-closed" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"/>
                                    </svg>
                                </button>
                            </div>

                            {{-- Bouton de connexion --}}
                            <button
                                type="submit"
                                class="w-full py-4 bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white font-semibold rounded-xl transition-all duration-200 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 focus:ring-offset-transparent shadow-lg"
                            >
                                Se connecter
                            </button>

                            {{-- Lien mot de passe oublié --}}
                            <div class="text-center">
                                <a href="#" class="text-gray-400 hover:text-white transition-colors duration-200 text-sm">
                                    Mot de passe oublié ?
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Script pour toggle du mot de passe --}}
<script>
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const eyeOpen = document.getElementById('eye-open');
    const eyeClosed = document.getElementById('eye-closed');

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeOpen.classList.add('hidden');
        eyeClosed.classList.remove('hidden');
    } else {
        passwordInput.type = 'password';
        eyeOpen.classList.remove('hidden');
        eyeClosed.classList.add('hidden');
    }
}
</script>
@endsection
