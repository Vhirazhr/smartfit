@extends('layouts.app')

@section('title', 'SMARTfit - Let\'s Get Started')

@section('content')
<section class="smartfit-start">
    <div class="smartfit-start-container">
        
        <!-- Progress Indicator -->
        <div class="progress-indicator">
            <div class="progress-step active">
                <span class="step-number">1</span>
                <span class="step-label">Start</span>
            </div>
            <div class="progress-line"></div>
            <div class="progress-step">
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
        <div class="start-card">
            <div class="card-icon">
                <i class="fa-regular fa-sparkles"></i>
            </div>
            <h1 class="card-title">Welcome to <span>SMARTfit</span></h1>
            <p class="card-description">
                Our expert system uses <strong>Forward Chaining method</strong> to analyze your body proportions 
                and recommend outfits that flatter your unique shape.
            </p>
            
            <div class="feature-list">
                <div class="feature">
                    <i class="fa-regular fa-ruler"></i>
                    <div>
                        <h4>Anthropometric Data</h4>
                        <p>Input your bust, waist, and hip measurements</p>
                    </div>
                </div>
                <div class="feature">
                    <i class="fa-regular fa-chart-line"></i>
                    <div>
                        <h4>Scientific Classification</h4>
                        <p>Based on Pandarum et al. (2020) with 81.9% accuracy</p>
                    </div>
                </div>
                <div class="feature">
                    <i class="fa-regular fa-shirt"></i>
                    <div>
                        <h4>Personalized Recommendations</h4>
                        <p>Get outfit suggestions tailored to your body type</p>
                    </div>
                </div>
            </div>
            
            <div class="start-actions">
                <a href="{{ route('smartfit.check') }}" class="btn-primary">
                    Get Started
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
                <a href="{{ route('landing') }}" class="btn-secondary">
                    Back to Home
                </a>
            </div>
            
            <div class="start-note">
                <i class="fa-regular fa-clock"></i>
                <span>Only takes 2 minutes</span>
                <i class="fa-regular fa-lock"></i>
                <span>Your data is private</span>
            </div>
        </div>
        
    </div>
</section>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/smartfit-start.css') }}">
@endpush