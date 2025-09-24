@extends('admin.layoutsAdmin.dashboardLayoutsAdmin')


@section('content')
    <section id="hero">
        <h1>{{ $contents['hero']->title ?? 'Bienvenue' }}</h1>
        <p>{{ $contents['hero']->description ?? '' }}</p>
        @if (!empty($contents['hero']->image))
            <img src="{{ asset('storage/' . $contents['hero']->image) }}" alt="Hero Image">
        @endif
    </section>

    <section id="about">
        <h2>{{ $contents['about']->title ?? 'À propos' }}</h2>
        <p>{{ $contents['about']->description ?? '' }}</p>
        @if (!empty($contents['about']->image))
            <img src="{{ asset('storage/' . $contents['about']->image) }}" alt="About Image">
        @endif
    </section>

    <section id="services">
        <h2>{{ $contents['services']->title ?? 'Services' }}</h2>
        <p>{{ $contents['services']->description ?? '' }}</p>
        @if (!empty($contents['services']->image))
            <img src="{{ asset('storage/' . $contents['services']->image) }}" alt="Services Image">
        @endif
    </section>
@endsection
