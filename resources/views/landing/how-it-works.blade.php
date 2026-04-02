@extends('layouts.app')

@section('title', 'How It Works - SMARTfit')

@section('content')
<section class="how-it-works-page" style="padding: 8rem 1.5rem 2rem; background: linear-gradient(180deg, #faf7f2 0%, #ffffff 100%);">
    <div style="max-width: 920px; margin: 0 auto; text-align: center;">
        <h1 style="font-family: 'Playfair Display', serif; font-size: clamp(2rem, 4vw, 3rem); color: #1f2937; margin-bottom: .75rem;">
            How SMARTfit Works
        </h1>
        <p style="font-family: 'Jost', sans-serif; color: #4b5563; margin: 0 auto 1.5rem; max-width: 700px;">
            Masukkan ukuran bust, waist, dan hip untuk mendapatkan klasifikasi morphotype dan rekomendasi outfit personal berdasarkan metode penelitian.
        </p>
        <a href="{{ route('landing') }}" class="btn-primary" style="display: inline-flex; align-items: center; gap: .5rem; margin-bottom: 2rem;">
            <i class="fa-solid fa-arrow-left"></i>
            Back To Home
        </a>
    </div>
</section>

@include('landing.partials.measure-body')
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/measure-body.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('js/measure-body.js') }}"></script>
@endpush
