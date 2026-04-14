@extends('layouts.app')

@section('title', 'SMARTfit - Do You Know Your Body Type?')

@section('content')
<section class="smartfit-check">
    <div class="smartfit-check-container">
        
        <!-- Progress Indicator -->
        <div class="progress-indicator">
            <div class="progress-step completed">
                <span class="step-number">1</span>
                <span class="step-label">Start</span>
            </div>
            <div class="progress-line"></div>
            <div class="progress-step active">
                <span class="step-number">2</span>
                <span class="step-label">Body Type</span>
            </div>
            <div class="progress-line"></div>
            <div class="progress-step">
                <span class="step-number">3</span>
                <span class="step-label">Result</span>
            </div>
        </div>
        
        <!-- Main Card -->
        <div class="check-card">
            <div class="card-header">
                <div class="card-badge">SMARTfit</div>
                <h2 class="card-title">Do You Know Your <span>Body Type</span>?</h2>
                <p class="card-subtitle">Select an option below to continue</p>
            </div>
            
            <div class="options-grid">
                
                <!-- Option 1: Yes, I Know (sekarang langsung ke halaman select) -->
                <a href="{{ route('smartfit.select') }}" class="option-card">
                    <div class="option-icon">
                        <i class="fa-regular fa-circle-check"></i>
                    </div>
                    <h3>Yes, I Know</h3>
                    <p>I already know my body type</p>
                    <span class="option-arrow">Continue →</span>
                </a>
                
                <!-- Option 2: No, Help Me -->
                <a href="{{ route('smartfit.input') }}" class="option-card">
                    <div class="option-icon">
                        <i class="fa-regular fa-compass"></i>
                    </div>
                    <h3>No, Help Me</h3>
                    <p>I'm not sure about my body type<br>Guide me to find out</p>
                    <span class="option-arrow">Continue →</span>
                </a>
                
            </div>
            
            <div class="card-note">
                <i class="fa-regular fa-lightbulb"></i>
                <p>Based on scientific research by Pandarum et al. (2020) with 81.9% accuracy.</p>
            </div>
        </div>
        
    </div>
</section>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/smartfit-check.css') }}">
@endpush