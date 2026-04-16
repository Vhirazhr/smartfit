<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SMARTfit - Find Your Perfect Fit')</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&family=Lora:wght@400;500;600;700&family=Jost:wght@400;500&family=Marcellus&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/hero.css') }}">
    <link rel="stylesheet" href="{{ asset('css/what-are-we.css') }}">
    <link rel="stylesheet" href="{{ asset('css/how-we-recommend.css') }}">
    <link rel="stylesheet" href="{{ asset('css/testimonials.css') }}">
    <link rel="stylesheet" href="{{ asset('css/newsletter.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/morphotypes.css') }}">
    <link rel="stylesheet" href="{{ asset('css/smartfit-check.css') }}">
<link rel="stylesheet" href="{{ asset('css/video-tutorial.css') }}">    
<link rel="stylesheet" href="{{ asset('css/gallery.css') }}">
<link rel="stylesheet" href="{{ asset('css/expert-tips.css') }}">
<link rel="stylesheet" href="{{ asset('css/stats.css') }}">
<link rel="stylesheet" href="{{ asset('css/size-guide.css') }}">
<link rel="stylesheet" href="{{ asset('css/blog-preview.css') }}">
<link rel="stylesheet" href="{{ asset('css/before-after.css') }}">
<link rel="stylesheet" href="{{ asset('css/measure-body.css') }}">
<link rel="stylesheet" href="{{ asset('css/gallery2.css') }}"> 
    @stack('styles')
</head>
<body>
    @include('landing.partials.navbar')
    
    <main>
        @yield('content')
    </main>
    
    @include('landing.partials.footer')
    
    <script src="{{ asset('js/main.js') }}"></script>
    @stack('scripts')
</body>
@stack('scripts')
<script>
    // Smooth scroll untuk anchor links dari halaman lain
    document.addEventListener('DOMContentLoaded', function() {
        // Cek apakah ada hash di URL
        if (window.location.hash) {
            const targetId = window.location.hash.substring(1);
            const targetElement = document.getElementById(targetId);
            
            if (targetElement) {
                setTimeout(() => {
                    targetElement.scrollIntoView({ behavior: 'smooth' });
                }, 100);
            }
        }
        
        // Handle klik navbar links
        const navLinks = document.querySelectorAll('.nav-links a');
        navLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                
                // Cek apakah ini anchor link ke halaman yang sama
                if (href.includes('#') && (href.startsWith('#') || href.startsWith(window.location.pathname + '#'))) {
                    e.preventDefault();
                    
                    const targetId = href.split('#')[1];
                    const targetElement = document.getElementById(targetId);
                    
                    if (targetElement) {
                        targetElement.scrollIntoView({ behavior: 'smooth' });
                        // Update URL tanpa reload
                        history.pushState(null, null, href);
                    }
                }
            });
        });
    });
</script>
</html>