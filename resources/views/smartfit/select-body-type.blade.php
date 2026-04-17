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

        <form action="{{ route('known.process') }}" method="POST" class="select-form">
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

                    <div class="body-type-grid">
                        <label class="option-box" data-shape="hourglass">
                            <input type="radio" name="body_type" value="Hourglass">
                            <div class="option-content">
                                <div class="shape-illustration hourglass-shape">
                                    <svg viewBox="0 0 100 120" width="70" height="84">
                                        <path d="M35,15 L35,40 Q50,50 35,60 L35,90 Q45,100 35,105 L65,105 Q55,100 65,90 L65,60 Q50,50 65,40 L65,15 Z"
                                              fill="none" stroke="#C5B09F" stroke-width="2.5" stroke-linecap="round"/>
                                        <circle cx="50" cy="50" r="10" fill="none" stroke="#C5B09F" stroke-width="2"/>
                                        <line x1="50" y1="15" x2="50" y2="105" stroke="#C5B09F" stroke-width="1" stroke-dasharray="3" opacity="0.5"/>
                                    </svg>
                                </div>
                                <div class="option-info">
                                    <span class="option-name">Hourglass</span>
                                    <span class="option-desc">Balanced bust and hips with a clearly defined waist.</span>
                                </div>
                                <div class="option-check">
                                    <i class="fa-regular fa-circle-check"></i>
                                </div>
                            </div>
                        </label>

                        <label class="option-box" data-shape="rectangle">
                            <input type="radio" name="body_type" value="Rectangle">
                            <div class="option-content">
                                <div class="shape-illustration rectangle-shape">
                                    <svg viewBox="0 0 100 120" width="70" height="84">
                                        <rect x="30" y="15" width="40" height="90" rx="8"
                                              fill="none" stroke="#C5B09F" stroke-width="2.5" stroke-linecap="round"/>
                                        <line x1="30" y1="45" x2="70" y2="45" stroke="#C5B09F" stroke-width="1" stroke-dasharray="3" opacity="0.5"/>
                                        <line x1="50" y1="15" x2="50" y2="105" stroke="#C5B09F" stroke-width="1" stroke-dasharray="3" opacity="0.5"/>
                                    </svg>
                                </div>
                                <div class="option-info">
                                    <span class="option-name">Rectangle</span>
                                    <span class="option-desc">Bust and hips are balanced with minimal waist definition.</span>
                                </div>
                                <div class="option-check">
                                    <i class="fa-regular fa-circle-check"></i>
                                </div>
                            </div>
                        </label>

                        <label class="option-box" data-shape="spoon">
                            <input type="radio" name="body_type" value="Spoon">
                            <div class="option-content">
                                <div class="shape-illustration spoon-shape">
                                    <svg viewBox="0 0 100 120" width="70" height="84">
                                        <path d="M35,15 L35,45 Q40,55 35,65 L35,95 Q40,105 35,110 L65,110 Q60,105 65,95 L65,65 Q60,55 65,45 L65,15 Z"
                                              fill="none" stroke="#C5B09F" stroke-width="2.5" stroke-linecap="round"/>
                                        <circle cx="50" cy="35" r="8" fill="none" stroke="#C5B09F" stroke-width="2"/>
                                        <line x1="50" y1="15" x2="50" y2="110" stroke="#C5B09F" stroke-width="1" stroke-dasharray="3" opacity="0.5"/>
                                    </svg>
                                </div>
                                <div class="option-info">
                                    <span class="option-name">Spoon</span>
                                    <span class="option-desc">Hips are wider than the bust with a defined waist.</span>
                                </div>
                                <div class="option-check">
                                    <i class="fa-regular fa-circle-check"></i>
                                </div>
                            </div>
                        </label>

                        <label class="option-box" data-shape="triangle">
                            <input type="radio" name="body_type" value="Triangle">
                            <div class="option-content">
                                <div class="shape-illustration triangle-shape">
                                    <svg viewBox="0 0 100 120" width="70" height="84">
                                        <polygon points="50,15 30,105 70,105"
                                                 fill="none" stroke="#C5B09F" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        <line x1="30" y1="80" x2="70" y2="80" stroke="#C5B09F" stroke-width="1" stroke-dasharray="3" opacity="0.5"/>
                                        <line x1="50" y1="15" x2="50" y2="105" stroke="#C5B09F" stroke-width="1" stroke-dasharray="3" opacity="0.5"/>
                                    </svg>
                                </div>
                                <div class="option-info">
                                    <span class="option-name">Triangle (Pear)</span>
                                    <span class="option-desc">Hips are wider than shoulders with a defined waist.</span>
                                </div>
                                <div class="option-check">
                                    <i class="fa-regular fa-circle-check"></i>
                                </div>
                            </div>
                        </label>

                        <label class="option-box" data-shape="inverted">
                            <input type="radio" name="body_type" value="Inverted Triangle">
                            <div class="option-content">
                                <div class="shape-illustration inverted-shape">
                                    <svg viewBox="0 0 100 120" width="70" height="84">
                                        <polygon points="30,15 70,15 50,105"
                                                 fill="none" stroke="#C5B09F" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        <line x1="30" y1="35" x2="70" y2="35" stroke="#C5B09F" stroke-width="1" stroke-dasharray="3" opacity="0.5"/>
                                        <line x1="50" y1="15" x2="50" y2="105" stroke="#C5B09F" stroke-width="1" stroke-dasharray="3" opacity="0.5"/>
                                    </svg>
                                </div>
                                <div class="option-info">
                                    <span class="option-name">Inverted Triangle</span>
                                    <span class="option-desc">Shoulders or bust are broader than the hips.</span>
                                </div>
                                <div class="option-check">
                                    <i class="fa-regular fa-circle-check"></i>
                                </div>
                            </div>
                        </label>
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
                                    <i class="fa-solid fa-shirt"></i>
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

// Panggil function ini saat submit
const submitBtn = document.getElementById('submitRecommendationBtn');
if (submitBtn) {
    submitBtn.addEventListener('click', function() {
        saveToLocalStorage();
    });
}
</script>
@endpush