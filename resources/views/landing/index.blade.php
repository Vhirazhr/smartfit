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
    
    <!-- 5. SIZE GUIDE - Panduan Ukuran  -->
    @include('landing.partials.size-guide')

    <!-- 5.5. VIDEO TUTORIAL - Cara Mengukur Badan -->
@include('landing.partials.video-tutorial')
    
    <!-- 6. BODY TYPE QUIZ - Interaktif -->
    @include('landing.partials.morphotypes')

    <!-- 7. MEASURE BODY - Integrated Analyzer -->
    
    
    <!-- 8. FASHION GALLERY - Inspirasi Outfit -->
    @include('landing.partials.gallery')
    
    
    <!-- 10. EXPERT TIPS - Tips dari Stylist -->
    @include('landing.partials.expert-tips')
    
    <!-- 11. TESTIMONIALS - Testimoni Pengguna -->
    
    <!-- 12. BLOG PREVIEW - Artikel Terkini -->
    @include('landing.partials.blog-preview')
    
    <!-- 13. NEWSLETTER - Subscribe -->

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
                    alert('Terima kasih telah berlangganan!');
                    newsletterInput.value = '';
                } else {
                    alert('Masukkan email yang valid');
                }
            });
        }
        const sections = document.querySelectorAll('section[id]');
const navLinks = document.querySelectorAll('.nav-links a[href^="#"]');
const homeLink = document.querySelector('.nav-links a[href="{{ route('landing') }}"]');

window.addEventListener('scroll', () => {
    let current = '';

    sections.forEach(section => {
        const sectionTop = section.offsetTop - 120;
        const sectionHeight = section.offsetHeight;

        if (
            window.scrollY >= sectionTop &&
            window.scrollY < sectionTop + sectionHeight
        ) {
            current = section.getAttribute('id');
        }
    });

    // hapus active dari semua menu
    document.querySelectorAll('.nav-links a').forEach(link => {
        link.classList.remove('active');
    });

    // kalau masih di atas halaman, Home aktif
    if (window.scrollY < 300) {
        homeLink?.classList.add('active');
        return;
    }

    // aktifkan menu section yang sedang dibuka
    navLinks.forEach(link => {
        if (link.getAttribute('href') === `#${current}`) {
            link.classList.add('active');
        }
    });
});
    });
</script>
@endpush