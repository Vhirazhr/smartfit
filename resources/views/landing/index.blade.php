@extends('layouts.app')

@section('title', 'SMARTfit - Expert Fashion System')

@section('content')
 <!-- 1. HERO SECTION -->
    @include('landing.partials.hero')
    
    <!-- 2. WHAT ARE WE? - Penjelasan Sistem -->
    @include('landing.partials.what-are-we')
    
    <!-- 3. STATS COUNTER - Bukti Sosial -->
    @include('landing.partials.stats')
    
    <!-- 4. HOW WE RECOMMEND - Cara Kerja -->
    @include('landing.partials.how-we-recommend')
    
    <!-- 5. BODY TYPE QUIZ - Interaktif -->
    @include('landing.partials.morphotypes')
    
    <!-- 6. FASHION GALLERY - Inspirasi Outfit -->
    @include('landing.partials.gallery')
    
    <!-- 7. BEFORE AFTER TRANSFORMATION - Contoh Nyata -->
    @include('landing.partials.before-after')
    
    <!-- 8. EXPERT TIPS - Tips dari Stylist -->
    @include('landing.partials.expert-tips')
    
    <!-- 9. SIZE GUIDE - Panduan Ukuran -->
    @include('landing.partials.size-guide')
    
    <!-- 10. TESTIMONIALS - Testimoni Pengguna -->
    @include('landing.partials.testimonials')
    
    <!-- 11. BLOG PREVIEW - Artikel Terkini -->
    @include('landing.partials.blog-preview')
    
    <!-- 12. NEWSLETTER - Subscribe -->
    @include('landing.partials.newsletter')
@endsection

@push('scripts')
<script>
    // Inisialisasi untuk landing page
    document.addEventListener('DOMContentLoaded', function() {
        // Navbar scroll effect
        const navbar = document.getElementById('navbar');
        
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                navbar?.classList.add('scrolled');
            } else {
                navbar?.classList.remove('scrolled');
            }
        });
        
        // Discover button handler
        const discoverBtn = document.getElementById('discoverBtn');
        if (discoverBtn) {
            discoverBtn.addEventListener('click', function() {
                const section = document.querySelector('.what-are-we');
                if (section) {
                    section.scrollIntoView({ behavior: 'smooth' });
                }
            });
        }
        
        // Testimonial slider
        const dots = document.querySelectorAll('.dot');
        const cards = document.querySelectorAll('.testimonial-card');
        
        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                dots.forEach(d => d.classList.remove('active'));
                dot.classList.add('active');
                cards.forEach(card => card.classList.remove('active'));
                cards[index]?.classList.add('active');
            });
        });
        
        // Newsletter form
        const newsletterBtn = document.querySelector('.newsletter-btn');
        const newsletterInput = document.querySelector('.newsletter-input');
        
        if (newsletterBtn) {
            newsletterBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const email = newsletterInput?.value;
                if (email && email.includes('@')) {
                    // Kirim ke backend nanti
                    alert('Terima kasih telah berlangganan!');
                    newsletterInput.value = '';
                } else {
                    alert('Masukkan email yang valid');
                }
            });
        }
    });
</script>
@endpush