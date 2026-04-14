@extends('layouts.app')

@section('title', 'SMARTfit - Select Your Body Type')

@section('content')
<section class="smartfit-select">
    <div class="smartfit-select-container">
        
        <!-- Progress Indicator -->
        <div class="progress-indicator">
            <div class="progress-step completed">
                <span class="step-number">1</span>
                <span class="step-label">Start</span>
            </div>
            <div class="progress-line"></div>
            <div class="progress-step completed">
                <span class="step-number">2</span>
                <span class="step-label">Body Type</span>
            </div>
            <div class="progress-line"></div>
            <div class="progress-step active">
                <span class="step-number">3</span>
                <span class="step-label">Preferences</span>
            </div>
            <div class="progress-line"></div>
            <div class="progress-step">
                <span class="step-number">4</span>
                <span class="step-label">Result</span>
            </div>
        </div>
        
        <!-- Form -->
        <form action="{{ route('smartfit.known') }}" method="POST" class="select-form">
            @csrf
            
            <div class="form-card">
                <div class="card-header">
                    <div class="card-icon">
                        <i class="fa-regular fa-user"></i>
                    </div>
                    <h2 class="card-title">Tell Us About <span>Yourself</span></h2>
                    <p class="card-subtitle">Select your body type and style preferences</p>
                </div>
                
                <!-- Step 1: Body Type dengan Ilustrasi -->
                <div class="form-section">
                    <div class="section-label">
                        <span class="step-badge">01</span>
                        <h3>Body Type</h3>
                    </div>
                    <div class="body-type-grid">
                        
                        <!-- Hourglass -->
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
                                    <span class="option-desc">Balanced bust & hips, defined waist</span>
                                </div>
                                <div class="option-check">
                                    <i class="fa-regular fa-circle-check"></i>
                                </div>
                            </div>
                        </label>
                        
                        <!-- Rectangle -->
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
                                    <span class="option-desc">Balanced proportions, minimal waist</span>
                                </div>
                                <div class="option-check">
                                    <i class="fa-regular fa-circle-check"></i>
                                </div>
                            </div>
                        </label>
                        
                        <!-- Spoon -->
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
                                    <span class="option-desc">Wider hips, defined waist</span>
                                </div>
                                <div class="option-check">
                                    <i class="fa-regular fa-circle-check"></i>
                                </div>
                            </div>
                        </label>
                        
                        <!-- Triangle (Pear) -->
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
                                    <span class="option-desc">Hips wider than shoulders</span>
                                </div>
                                <div class="option-check">
                                    <i class="fa-regular fa-circle-check"></i>
                                </div>
                            </div>
                        </label>
                        
                        <!-- Inverted Triangle -->
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
                                    <span class="option-desc">Shoulders wider than hips</span>
                                </div>
                                <div class="option-check">
                                    <i class="fa-regular fa-circle-check"></i>
                                </div>
                            </div>
                        </label>
                        
                    </div>
                </div>
                
                <!-- Step 2: Style Preference -->
                <div class="form-section">
                    <div class="section-label">
                        <span class="step-badge">02</span>
                        <h3>Style Preference</h3>
                    </div>
                    <div class="style-grid">
                        <label class="option-chip">
                            <input type="radio" name="style_preference" value="Casual">
                            <span class="chip-content">
                                <i class="fa-regular fa-shirt"></i>
                                Casual
                            </span>
                        </label>
                        <label class="option-chip">
                            <input type="radio" name="style_preference" value="Formal">
                            <span class="chip-content">
                                <i class="fa-regular fa-briefcase"></i>
                                Formal
                            </span>
                        </label>
                        <label class="option-chip">
                            <input type="radio" name="style_preference" value="Bohemian">
                            <span class="chip-content">
                                <i class="fa-regular fa-feather"></i>
                                Bohemian
                            </span>
                        </label>
                        <label class="option-chip">
                            <input type="radio" name="style_preference" value="Classic">
                            <span class="chip-content">
                                <i class="fa-regular fa-clock"></i>
                                Classic
                            </span>
                        </label>
                        <label class="option-chip">
                            <input type="radio" name="style_preference" value="Sporty">
                            <span class="chip-content">
                                <i class="fa-regular fa-futbol"></i>
                                Sporty
                            </span>
                        </label>
                    </div>
                </div>
                
                <!-- Step 3: Color Tone -->
                <div class="form-section">
                    <div class="section-label">
                        <span class="step-badge">03</span>
                        <h3>Color Tone Preference</h3>
                    </div>
                    <div class="color-grid">
                        <label class="color-option" data-color="light">
                            <input type="radio" name="color_tone" value="Light">
                            <div class="color-swatch" style="background: #FFF5E6;"></div>
                            <span>Light</span>
                        </label>
                        <label class="color-option" data-color="bright">
                            <input type="radio" name="color_tone" value="Bright">
                            <div class="color-swatch" style="background: #FF4444;"></div>
                            <span>Bright</span>
                        </label>
                        <label class="color-option" data-color="neutral">
                            <input type="radio" name="color_tone" value="Neutral">
                            <div class="color-swatch" style="background: #8B8B8B;"></div>
                            <span>Neutral</span>
                        </label>
                        <label class="color-option" data-color="dark">
                            <input type="radio" name="color_tone" value="Dark">
                            <div class="color-swatch" style="background: #1B1B1B;"></div>
                            <span>Dark</span>
                        </label>
                        <label class="color-option" data-color="earth">
                            <input type="radio" name="color_tone" value="Earth">
                            <div class="color-swatch" style="background: #8B6914;"></div>
                            <span>Earth</span>
                        </label>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="form-actions">
                    <a href="{{ route('smartfit.check') }}" class="btn-back">
                        <i class="fa-solid fa-arrow-left"></i> Back
                    </a>
                    <button type="submit" class="btn-continue">
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
    // Add visual feedback for selected options
    document.querySelectorAll('.option-box, .option-chip, .color-option').forEach(option => {
        const radio = option.querySelector('input[type="radio"]');
        if (radio) {
            radio.addEventListener('change', function() {
                // Remove selected class from siblings in the same group
                const parent = this.closest('.body-type-grid, .style-grid, .color-grid');
                if (parent) {
                    parent.querySelectorAll('.option-box, .option-chip, .color-option').forEach(el => {
                        el.classList.remove('selected');
                    });
                }
                if (this.checked) {
                    option.classList.add('selected');
                }
            });
        }
    });
</script>
@endpush