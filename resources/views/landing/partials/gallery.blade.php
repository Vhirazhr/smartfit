<section class="gallery-section">
    <div class="gallery-container">
        <div class="gallery-layout">

            <!-- Left Content -->
            <div class="gallery-intro">
                <div class="gallery-tag">FASHION INSPIRATION</div>

                <h2 class="gallery-title">
                    Outfit Ideas for Every Body Type
                </h2>

                <p class="gallery-subtitle">
                    Discover looks that celebrate your unique shape
                </p>

                <a href="{{ route('gallery') }}" class="btn-view-all">
                    View All Inspiration
                    <span>→</span>
                </a>
            </div>

            <!-- Right Slider -->
            <div class="gallery-slider-area">
                <div class="gallery-slider-track">

                    @forelse($galleryItems as $item)
                        <a href="{{ route('gallery') }}" class="gallery-item">
                            <img
                                src="{{ $item['image_url'] }}"
                                alt="{{ $item['title'] }}"
                                loading="lazy"
                                onerror="this.src='https://placehold.co/600x800/f5f0ed/1B1B1B?text=No+Image'"
                            >

                            <div class="gallery-overlay">
                                <span class="gallery-category">
                                    {{ $item['body_label'] }}
                                </span>

                                <h4>{{ $item['title'] }}</h4>

                                <p>
                                    {{ $item['category_name'] ?? 'Fashion Inspiration' }}
                                </p>
                            </div>
                        </a>
                    @empty
                        <div class="gallery-empty">
                            No fashion inspiration available.
                        </div>
                    @endforelse

                    {{-- Duplicate untuk infinite slider --}}
                    @foreach($galleryItems as $item)
                        <a href="{{ route('gallery') }}" class="gallery-item">
                            <img
                                src="{{ $item['image_url'] }}"
                                alt="{{ $item['title'] }}"
                                loading="lazy"
                                onerror="this.src='https://placehold.co/600x800/f5f0ed/1B1B1B?text=No+Image'"
                            >

                            <div class="gallery-overlay">
                                <span class="gallery-category">
                                    {{ $item['body_label'] }}
                                </span>

                                <h4>{{ $item['title'] }}</h4>

                                <p>
                                    {{ $item['category_name'] ?? 'Fashion Inspiration' }}
                                </p>
                            </div>
                        </a>
                    @endforeach

                </div>
            </div>

        </div>
    </div>
</section>