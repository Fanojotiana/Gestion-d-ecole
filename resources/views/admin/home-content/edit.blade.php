@extends('admin.layoutsAdmin.dashboardLayoutsAdmin')

@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">

            {{-- Titre + breadcrumb --}}
            <div class="page-header mb-4">
                <h3 class="page-title">Contenu de la page d’accueil</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Page d’accueil</li>
                </ul>
            </div>

            {{-- Notifications Toastr --}}
            @if (session('success'))
                <script>
                    toastr.success(@json(session('success')));
                </script>
            @endif
            @if (session('error'))
                <script>
                    toastr.error(@json(session('error')));
                </script>
            @endif

            <form method="POST" action="{{ route('admin.home-content.update') }}" enctype="multipart/form-data">
                @csrf

                @php
                    $sections = ['hero', 'about', 'services'];
                @endphp

                @foreach ($sections as $section)
                    @php $content = $contents[$section] ?? null; @endphp

                    <div class="card mb-4 shadow">
                        <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                            <h5 class="mb-0">{{ ucfirst($section) }}</h5>
                        </div>

                        <div class="card-body">

                            {{-- Titre --}}
                            <div class="mb-3">
                                <label class="form-label">Titre</label>
                                <input type="text" name="sections[{{ $section }}][title]" class="form-control"
                                    value="{{ old("sections.$section.title", $content->title ?? '') }}">
                            </div>

                            {{-- Description --}}
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="sections[{{ $section }}][description]" class="form-control" rows="4">{{ old("sections.$section.description", $content->description ?? '') }}</textarea>
                            </div>

                            {{-- Image --}}
                            <div class="mb-3">
                                <label class="form-label">Image</label>
                                <input type="file" name="sections[{{ $section }}][image]" class="form-control">

                                @if (!empty($content->image))
                                    <img src="{{ asset('storage/' . $content->image) }}" alt="{{ $section }}"
                                        class="mt-3 rounded shadow-sm border" style="max-width: 200px;">
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
