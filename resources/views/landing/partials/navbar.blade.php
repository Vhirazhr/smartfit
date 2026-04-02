@php
    $isLandingRoute = request()->routeIs('landing') || request()->routeIs('home');
@endphp

<nav class="navbar {{ $isLandingRoute ? '' : 'scrolled' }}" id="navbar" data-static="{{ $isLandingRoute ? 'false' : 'true' }}">
    <div class="logo">
        <span class="logo-light">SMART</span><span class="logo-bold">fit</span>
    </div>
    <div class="nav-links">
        <a href="{{ route('landing') }}" data-nav="home" class="{{ $isLandingRoute ? 'active' : '' }}">Home</a>
        <a href="{{ $isLandingRoute ? '#morphotypes' : route('landing') . '#morphotypes' }}" data-nav="morphotypes">Body Types</a>
        <a href="{{ route('how-it-works') }}" data-nav="how-it-works" class="{{ request()->routeIs('how-it-works') ? 'active' : '' }}">How It Works</a>
        <a href="{{ $isLandingRoute ? '#gallery' : route('landing') . '#gallery' }}" data-nav="gallery">Gallery</a>
        <a href="{{ $isLandingRoute ? '#contact' : route('landing') . '#contact' }}" data-nav="contact">Contact</a>
    </div>
    <div class="nav-icons">
        <i class="fa-regular fa-user"></i>
        <i class="fa-regular fa-heart"></i>
    </div>
    <div class="mobile-menu-btn" id="mobileMenuBtn">
        <i class="fa-solid fa-bars"></i>
    </div>
</nav>