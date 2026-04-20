@extends('layouts.app_nonavbar')

@section('title', 'SMARTfit - Admin Login')

@section('content')
<section class="admin-login-premium">
    <div class="premium-container">
        
        <!-- Left Side: Fashion Image & Quote -->
        <div class="premium-image">
            <div class="image-overlay"></div>
            <img src="https://images.unsplash.com/photo-1539109136881-3be0616acf4b?w=800&h=900&fit=crop" 
                 alt="Fashion Model" 
                 class="fashion-img">
            <div class="image-quote">
                <i class="fas fa-quote-right"></i>
                <p>"Fashion is not about the trend,<br>the proportion does."</p>
                <span>— SMARTfit Expert System</span>
            </div>
        </div>

        <!-- Right Side: Login Form -->
        <div class="premium-form">
            <div class="form-card">
                <!-- Back Button -->
                <a href="{{ route('landing') }}" class="back-home">
                    <i class="fas fa-arrow-left"></i>
                    <span>Back to Home</span>
                </a>

                <div class="form-header">
                    <div class="logo-icon">
                        <i class="fas fa-gem"></i>
                    </div>
                    <h2>Welcome Back, Admin</h2>
                    <p>Login to manage your SMARTfit dashboard</p>
                </div>

                @if(session('error'))
                    <div class="alert-premium">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.login') }}" class="premium-login-form">
                    @csrf

                    <div class="input-group">
                        <label>
                            <i class="fas fa-envelope"></i>
                            Email Address
                        </label>
                        <input type="email" name="email" placeholder="admin@smartfit.com" required autofocus>
                    </div>

                    <div class="input-group">
                        <label>
                            <i class="fas fa-lock"></i>
                            Password
                        </label>
                        <div class="password-field">
                            <input type="password" name="password" id="password" placeholder="Enter your password" required>
                            <button type="button" class="toggle-eye" id="togglePassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="form-footer-options">
                        <label class="checkbox-custom">
                            <input type="checkbox" name="remember">
                            <span class="check-box"></span>
                            <span class="check-label">Remember me</span>
                        </label>
                        <a href="#" class="forgot-link">Forgot password?</a>
                    </div>

                    <button type="submit" class="submit-btn">
                        <span>Sign In</span>
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </form>

                <div class="form-divider">
                    <span>Secure Access Only</span>
                </div>

                <div class="admin-info">
                    <i class="fas fa-shield-alt"></i>
                    <p>This area is restricted to authorized personnel only.</p>
                </div>
            </div>
        </div>
        
    </div>
</section>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin-login.css') }}">
@endpush

@push('scripts')
<script>
    const toggleBtn = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    
    if (toggleBtn && passwordInput) {
        toggleBtn.addEventListener('click', function() {
            const type = passwordInput.type === 'password' ? 'text' : 'password';
            passwordInput.type = type;
            this.querySelector('i').className = type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
        });
    }
</script>
@endpush