<nav class="navbar" id="navbar">
    <div class="logo">
        <a href="{{ route('landing') }}" style="text-decoration: none;">
            <span class="logo-light">SMART</span><span class="logo-bold">fit</span>
        </a>
    </div>
    <div class="nav-links">
        @php
            $isLandingPage = request()->routeIs('landing');
        @endphp
        
        <a href="{{ route('landing') }}" class="{{ $isLandingPage ? 'active' : '' }}">Home</a>
        
        @if($isLandingPage)
            <a href="#morphotypes">Body Types</a>
            <a href="#how-we-recommend">How It Works</a>
        @else
            <a href="{{ route('landing') }}#morphotypes">Body Types</a>
            <a href="{{ route('landing') }}#how-we-recommend">How It Works</a>
        @endif
        
        <a href="{{ route('gallery') }}" class="{{ request()->routeIs('gallery') ? 'active' : '' }}">Gallery</a>
    </div>
</nav>