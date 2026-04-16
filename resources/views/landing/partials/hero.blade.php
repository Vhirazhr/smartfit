<section class="hero">
    <div class="hero-bg">
        <video autoplay muted loop playsinline preload="metadata" class="hero-video">
            <source src="{{ asset('videos/dior-campaign.mp4') }}" type="video/mp4">
        </video>
    </div>
    <div class="hero-content">
        <div class="hero-tag">
            <span class="hero-tag-light">SMART</span><span class="hero-tag-bold">fit</span>
        </div>
        <h1 class="hero-title">Fashion is Not About the Trend<br>The Proportion Does</h1>
        <p class="hero-subtitle">
            <span class="text-semibold">Find Your Fashion</span>
            <span class="text-italic">style</span>
            <span class="text-semibold">with us based on your body type</span>
        </p>
         <a href="{{ route('smartfit.start') }}" class="btn-discover">
            <span>DISCOVER YOUR FIT</span>
            <i class="fa-solid fa-arrow-right"></i>
        </a>
    </div>
</section>