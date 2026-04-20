<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - SMARTfit</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@500;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Instrument Sans', 'Manrope', sans-serif;
            background: #0a0a0a;
            color: #EDEDEC;
            min-height: 100vh;
        }

        /* Navbar - dengan gaya dari Laravel */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 24px 60px;
            background: rgba(10, 10, 10, 0.95);
            border-bottom: 1px solid #3E3E3A;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            backdrop-filter: blur(8px);
        }

        .logo {
            font-family: 'Bricolage Grotesque', sans-serif;
            font-size: 24px;
            font-weight: 800;
            letter-spacing: -0.5px;
            background: linear-gradient(135deg, #EDEDEC 0%, #a855f7 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .nav-links {
            display: flex;
            gap: 40px;
        }

        .nav-links a {
            color: #A1A09A;
            text-decoration: none;
            font-weight: 500;
            transition: 0.3s;
            font-size: 14px;
            letter-spacing: 0.5px;
        }

        .nav-links a:hover {
            color: #EDEDEC;
            border-bottom: 1px solid #a855f7;
            padding-bottom: 4px;
        }

        /* Main Container */
        .login-container {
            display: flex;
            min-height: 100vh;
            padding-top: 80px;
        }

        /* Left Side - Hero Section */
        .hero-section {
            flex: 1;
            background: #0a0a0a;
            padding: 80px 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 30% 50%, rgba(139, 92, 246, 0.08), transparent);
            pointer-events: none;
        }

        .badge {
            display: inline-block;
            background: rgba(139, 92, 246, 0.15);
            border: 1px solid rgba(139, 92, 246, 0.3);
            padding: 6px 14px;
            border-radius: 40px;
            font-size: 12px;
            font-weight: 600;
            color: #c084fc;
            margin-bottom: 24px;
            width: fit-content;
        }

        .hero-section h1 {
            font-family: 'Bricolage Grotesque', sans-serif;
            font-size: 56px;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 20px;
            letter-spacing: -1.5px;
        }

        .hero-section h1 span {
            background: linear-gradient(135deg, #EDEDEC, #c084fc);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .hero-section p {
            font-size: 18px;
            color: #A1A09A;
            line-height: 1.6;
            margin-bottom: 40px;
            max-width: 450px;
        }

        .features {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #A1A09A;
            font-size: 14px;
        }

        .feature-item i {
            color: #c084fc;
            font-size: 18px;
        }

        /* Right Side - Login Form dengan efek glassmorphism ala Laravel */
        .form-section {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            background: #0f0f0f;
            position: relative;
        }

        .form-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 70% 50%, rgba(168, 85, 247, 0.05), transparent);
            pointer-events: none;
        }

        .login-card {
            max-width: 420px;
            width: 100%;
            background: #161615;
            padding: 40px;
            border-radius: 12px;
            border: 1px solid #3E3E3A;
            position: relative;
            z-index: 1;
            box-shadow: inset 0px 0px 0px 1px rgba(255, 250, 237, 0.18);
            transition: all 0.75s cubic-bezier(0.4, 0, 0.2, 1);
            opacity: 1;
            transform: translateY(0);
        }

        .login-card.starting {
            opacity: 0;
            transform: translateY(20px);
        }

        .login-card h2 {
            font-family: 'Bricolage Grotesque', sans-serif;
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 8px;
            background: linear-gradient(135deg, #EDEDEC, #e9d5ff);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .login-card .subtitle {
            color: #A1A09A;
            margin-bottom: 32px;
            font-size: 14px;
        }

        .input-group {
            margin-bottom: 20px;
        }

        .input-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 13px;
            font-weight: 600;
            color: #EDEDEC;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #a855f7;
            font-size: 16px;
        }

        input {
            width: 100%;
            padding: 12px 14px 12px 42px;
            background: #0a0a0a;
            border: 1px solid #3E3E3A;
            border-radius: 8px;
            font-family: 'Instrument Sans', sans-serif;
            font-size: 14px;
            color: #EDEDEC;
            outline: none;
            transition: all 0.3s ease;
        }

        input:focus {
            border-color: #a855f7;
            background: #1a1a1a;
            box-shadow: 0 0 0 3px rgba(168, 85, 247, 0.15);
        }

        input::placeholder {
            color: #52525b;
        }

        .options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            font-size: 13px;
        }

        .checkbox {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #A1A09A;
            cursor: pointer;
        }

        .checkbox input {
            width: 16px;
            height: 16px;
            padding: 0;
            margin: 0;
            cursor: pointer;
            accent-color: #a855f7;
        }

        .forgot-link {
            color: #c084fc;
            text-decoration: none;
            transition: 0.3s;
            font-size: 13px;
        }

        .forgot-link:hover {
            color: #e9d5ff;
            text-decoration: underline;
        }

        button {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #7c3aed, #a855f7);
            border: none;
            border-radius: 8px;
            color: white;
            font-weight: 700;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'Instrument Sans', sans-serif;
            margin-bottom: 20px;
            position: relative;
            overflow: hidden;
        }

        button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: 0.5s;
        }

        button:hover::before {
            left: 100%;
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(124, 58, 237, 0.3);
        }

        .register-link {
            text-align: center;
            font-size: 13px;
            color: #A1A09A;
        }

        .register-link a {
            color: #c084fc;
            text-decoration: none;
            font-weight: 600;
            transition: 0.3s;
        }

        .register-link a:hover {
            color: #e9d5ff;
            text-decoration: underline;
        }

        .error {
            background: rgba(239, 68, 68, 0.12);
            border: 1px solid rgba(239, 68, 68, 0.3);
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            color: #fca5a5;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .error i {
            font-size: 16px;
            color: #ef4444;
        }

        /* Decorative elements ala Laravel */
        .decor-line {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, #a855f7, #7c3aed, transparent);
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-in {
            animation: fadeInUp 0.75s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        }

        /* Responsive */
        @media (max-width: 900px) {
            .navbar {
                padding: 18px 24px;
            }
            
            .nav-links {
                gap: 20px;
            }
            
            .login-container {
                flex-direction: column;
            }
            
            .hero-section {
                padding: 60px 32px;
                text-align: center;
                align-items: center;
            }
            
            .hero-section h1 {
                font-size: 42px;
            }
            
            .hero-section p {
                max-width: 100%;
            }
            
            .features {
                align-items: center;
            }
            
            .form-section {
                padding: 40px 24px;
            }
            
            .login-card {
                padding: 32px 24px;
            }
        }

        @media (max-width: 480px) {
            .hero-section h1 {
                font-size: 32px;
            }
            
            .login-card h2 {
                font-size: 28px;
            }
        }
    </style>
</head>
<body>

<!-- Navigation Bar - dengan gaya Laravel -->
<nav class="navbar">
    <div class="logo">SMARTFIT</div>
    <div class="nav-links">
        <a href="#">HOME</a>
        <a href="#">BODYTYPES</a>
        <a href="#">HOWITWORKS</a>
        <a href="#">GALLERY</a>
        <a href="#">CONTACT</a>
    </div>
</nav>

<!-- Main Login Container -->
<div class="login-container">
    <!-- Left Side - Branding -->
    <div class="hero-section">
        <div class="badge">
            <i class="fas fa-tshirt" style="margin-right: 6px;"></i> EXPERT FASHION SYSTEM
        </div>
        <h1>
            Fashion is <span>Not About</span><br>
            the Trend<br>
            The <span>Proportion</span> Does
        </h1>
        <p>Find your fashion style with us based on your body type. Discover the perfect fit that complements your unique proportions.</p>
        
        <div class="features">
            <div class="feature-item">
                <i class="fas fa-robot"></i>
                <span>AI-Powered Body Analysis</span>
            </div>
            <div class="feature-item">
                <i class="fas fa-magic"></i>
                <span>Personalized Style Recommendations</span>
            </div>
            <div class="feature-item">
                <i class="fas fa-chalkboard-user"></i>
                <span>Expert Fashion Consultation</span>
            </div>
        </div>
    </div>

    <!-- Right Side - Login Form dengan efek transisi ala Laravel -->
    <div class="form-section">
        <div class="login-card" id="loginCard">
            <h2>Welcome Back</h2>
            <p class="subtitle">Login to access your style dashboard</p>

            @if(session('error'))
                <div class="error">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <form method="POST" action="/admin/login">
                @csrf

                <div class="input-group">
                    <label>Email Address</label>
                    <div class="input-wrapper">
                        <i class="fas fa-envelope"></i>
                        <input type="email" name="email" placeholder="you@example.com" required>
                    </div>
                </div>

                <div class="input-group">
                    <label>Password</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" placeholder="Enter your password" required>
                    </div>
                </div>

                <div class="options">
                    <label class="checkbox">
                        <input type="checkbox" name="remember"> 
                        <span>Remember me</span>
                    </label>
                    <a href="#" class="forgot-link">Forgot password?</a>
                </div>

                <button type="submit">
                    <i class="fas fa-arrow-right-to-bracket" style="margin-right: 8px;"></i>
                    Sign In
                </button>

                <div class="register-link">
                    Don't have an account? <a href="#">Get Started</a>
                </div>
            </form>
        </div>
        <div class="decor-line"></div>
    </div>
</div>

<script>
    // Menambahkan efek fade-in seperti pada template Laravel
    document.addEventListener('DOMContentLoaded', function() {
        const loginCard = document.getElementById('loginCard');
        loginCard.classList.add('starting');
        
        // Force reflow
        loginCard.offsetHeight;
        
        loginCard.classList.remove('starting');
        loginCard.classList.add('animate-in');
    });
</script>

</body>
</html>