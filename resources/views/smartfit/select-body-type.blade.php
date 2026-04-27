@extends('layouts.app_nonavbar')

@section('title', 'SMARTfit - Select Your Body Type')

@section('content')
<section class="smartfit-select">
    <div class="smartfit-select-container">
        
        <!-- Progress Indicator -->
        <div class="progress-indicator">
            <div class="progress-step completed">
                <span class="step-number">1</span>
                <span class="step-label">Identify Your Body Type</span>
            </div>
            <div class="progress-line"></div>
            <div class="progress-step active">
                <span class="step-number">2</span>
                <span class="step-label">Style Preferences</span>
            </div>
            <div class="progress-line"></div>
            <div class="progress-step">
                <span class="step-number">3</span>
                <span class="step-label">Result</span>
            </div>
        </div>

        <form action="{{ route('known.get.recommendation') }}" method="POST" class="select-form">
            @csrf

            <div class="form-card">
                <div class="card-header">
                    <div class="card-icon">
                        <i class="fa-regular fa-user"></i>
                    </div>
                    <h2 class="card-title">Tell Us About <span>Yourself</span></h2>
                    <p class="card-subtitle">
                        Select your body type, preferred style, and color tone to get personalized outfit recommendations.
                    </p>
                </div>

                <!-- Section 1: Body Type -->
                <div class="form-section section-block">
                    <div class="section-label">
                        <span class="step-badge">01</span>
                        <div class="section-heading">
                            <h3>Body Type</h3>
                            <p class="section-helper">
                                Choose the body shape that best matches your proportions.
                            </p>
                        </div>
                    </div>
                    <div class="field-error" id="bodyTypeError"></div>

                    @php
                        $manualTypeMap = $manualBodyTypes ?? config('smartfit.manual_body_types', []);
                        $typeDescriptions = $descriptions ?? config('smartfit.descriptions', []);
                        $bodyTypeCards = [
                            ['label' => 'Hourglass', 'icon' => 'fa-hourglass-half'],
                            ['label' => 'Y', 'icon' => 'fa-caret-up'],
                            ['label' => 'Inverted Triangle', 'icon' => 'fa-play fa-rotate-270'],
                            ['label' => 'Spoon', 'icon' => 'fa-spoon'],
                            ['label' => 'Rectangle', 'icon' => 'fa-vector-square'],
                            ['label' => 'U', 'icon' => 'fa-circle'],
                            ['label' => 'Triangle', 'icon' => 'fa-caret-down'],
                            ['label' => 'Inverted U', 'icon' => 'fa-arrows-up-down'],
                            ['label' => 'Diamond', 'icon' => 'fa-gem'],
                        ];
                    @endphp

                    <div class="body-type-grid">
                        @foreach($bodyTypeCards as $card)
                            @php
                                $morphotypeKey = $manualTypeMap[$card['label']] ?? 'undefined';
                                $description = $typeDescriptions[$morphotypeKey] ?? 'Body profile description is available.';
                            @endphp
                            <label class="option-box" data-shape="{{ strtolower(str_replace(' ', '-', $card['label'])) }}">
                                <input type="radio" name="body_type" value="{{ $card['label'] }}" @checked(old('body_type') === $card['label'])>
                                <div class="option-content">
                                    <div class="shape-illustration">
                                        <i class="fa-solid {{ $card['icon'] }}"></i>
                                    </div>
                                    <div class="option-info">
                                        <span class="option-name">{{ $card['label'] }}</span>
                                        <span class="option-desc">{{ $description }}</span>
                                    </div>
                                    <div class="option-check">
                                        <i class="fa-regular fa-circle-check"></i>
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="section-divider">
                    <span>Personal Style</span>
                </div>

                <!-- Section 2: Style Preference -->
                <div class="form-section section-block section-soft">
                    <div class="section-label">
                        <span class="step-badge">02</span>
                        <div class="section-heading">
                            <h3>Style Preference</h3>
                            <p class="section-helper">
                                Choose the fashion style that best matches your personality and daily look.
                            </p>
                        </div>
                    </div>
                    <div class="field-error" id="stylePreferenceError"></div>

                    <div class="style-card-grid">
                        <label class="style-card">
                            <input type="radio" name="style_preference" value="Casual">
                            <div class="style-card-content">
                                <div class="style-card-icon">
                                    <i class="fa-solid fa-tshirt"></i>
                                </div>
                                <div class="style-card-text">
                                    <span class="style-card-title">Casual</span>
                                    <p class="style-card-desc">
                                        Relaxed, simple, and comfortable for everyday wear.
                                    </p>
                                    <span class="style-card-example">T-shirt • Jeans • Sneakers</span>
                                </div>
                                <div class="style-card-check">
                                    <i class="fa-regular fa-circle-check"></i>
                                </div>
                            </div>
                        </label>

                        <label class="style-card">
                            <input type="radio" name="style_preference" value="Formal">
                            <div class="style-card-content">
                                <div class="style-card-icon">
                                    <i class="fa-solid fa-briefcase"></i>
                                </div>
                                <div class="style-card-text">
                                    <span class="style-card-title">Formal</span>
                                    <p class="style-card-desc">
                                        Neat, elegant, and polished for work or special occasions.
                                    </p>
                                    <span class="style-card-example">Blazer • Trousers • Heels</span>
                                </div>
                                <div class="style-card-check">
                                    <i class="fa-regular fa-circle-check"></i>
                                </div>
                            </div>
                        </label>

                        <label class="style-card">
                            <input type="radio" name="style_preference" value="Bohemian">
                            <div class="style-card-content">
                                <div class="style-card-icon">
                                    <i class="fa-solid fa-leaf"></i>
                                </div>
                                <div class="style-card-text">
                                    <span class="style-card-title">Bohemian</span>
                                    <p class="style-card-desc">
                                        Free-spirited, artistic, and soft with layered details.
                                    </p>
                                    <span class="style-card-example">Flowy Dress • Layered • Earth Tone</span>
                                </div>
                                <div class="style-card-check">
                                    <i class="fa-regular fa-circle-check"></i>
                                </div>
                            </div>
                        </label>

                        <label class="style-card">
                            <input type="radio" name="style_preference" value="Classic">
                            <div class="style-card-content">
                                <div class="style-card-icon">
                                    <i class="fa-solid fa-gem"></i>
                                </div>
                                <div class="style-card-text">
                                    <span class="style-card-title">Classic</span>
                                    <p class="style-card-desc">
                                        Timeless, refined, and balanced with clean silhouettes.
                                    </p>
                                    <span class="style-card-example">Midi Dress • Neutral Tone • Pumps</span>
                                </div>
                                <div class="style-card-check">
                                    <i class="fa-regular fa-circle-check"></i>
                                </div>
                            </div>
                        </label>

                        <label class="style-card">
                            <input type="radio" name="style_preference" value="Sporty">
                            <div class="style-card-content">
                                <div class="style-card-icon">
                                    <i class="fa-solid fa-dumbbell"></i>
                                </div>
                                <div class="style-card-text">
                                    <span class="style-card-title">Sporty</span>
                                    <p class="style-card-desc">
                                        Active, practical, and energetic with easy movement.
                                    </p>
                                    <span class="style-card-example">Hoodie • Jogger • Running Shoes</span>
                                </div>
                                <div class="style-card-check">
                                    <i class="fa-regular fa-circle-check"></i>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('smartfit.check') }}" class="btn-back">
                        <i class="fa-solid fa-arrow-left"></i> Back
                    </a>
                    <button type="submit" class="btn-continue" id="submitRecommendationBtn">
                        Get Recommendations <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/smartfit-select.css') }}">
@endpush

@push('scripts')
<script>
    const form = document.querySelector('.select-form');
    const submitBtn = document.getElementById('submitRecommendationBtn');
    const bodyTypeError = document.getElementById('bodyTypeError');
    const stylePreferenceError = document.getElementById('stylePreferenceError');

    function updateSelectedState() {
        document.querySelectorAll('.option-box, .style-card, .option-chip, .color-option').forEach(option => {
            const radio = option.querySelector('input[type="radio"]');
            option.classList.remove('selected');

            if (radio && radio.checked) {
                option.classList.add('selected');
            }
        });
    }

    function clearErrors() {
        if (bodyTypeError) bodyTypeError.textContent = '';
        if (stylePreferenceError) stylePreferenceError.textContent = '';

        document.querySelectorAll('.option-box, .style-card').forEach(el => {
            el.classList.remove('input-error');
        });
    }

    document.querySelectorAll('.option-box input, .style-card input, .option-chip input, .color-option input').forEach(input => {
        input.addEventListener('change', function () {
            updateSelectedState();
            clearErrors();
        });
    });

    if (form) {
        form.addEventListener('submit', function (e) {
            clearErrors();

            const selectedBodyType = document.querySelector('input[name="body_type"]:checked');
            const selectedStylePreference = document.querySelector('input[name="style_preference"]:checked');

            let hasError = false;

            if (!selectedBodyType) {
                e.preventDefault();
                hasError = true;

                if (bodyTypeError) {
                    bodyTypeError.textContent = 'Please select your body type first.';
                }

                document.querySelectorAll('.option-box').forEach(el => {
                    el.classList.add('input-error');
                });
            }

            if (!selectedStylePreference) {
                e.preventDefault();
                hasError = true;

                if (stylePreferenceError) {
                    stylePreferenceError.textContent = 'Please choose your style preference.';
                }

                document.querySelectorAll('.style-card').forEach(el => {
                    el.classList.add('input-error');
                });
            }

            if (hasError) {
                const firstError = document.querySelector('.field-error:not(:empty)');
                if (firstError) {
                    firstError.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }
            }
        });
    }

    updateSelectedState();
    function saveToLocalStorage() {
    const selectedBodyType = document.querySelector('input[name="body_type"]:checked');
    const selectedStyle = document.querySelector('input[name="style_preference"]:checked');
    
    if (selectedBodyType) {
        localStorage.setItem('smartfit_body_type', selectedBodyType.value);
    }
    
    if (selectedStyle) {
        localStorage.setItem('smartfit_style_preference', selectedStyle.value);
    }
}

    if (submitBtn) {
        submitBtn.addEventListener('click', function () {
            saveToLocalStorage();
        });
    }
</script>
@endpush
