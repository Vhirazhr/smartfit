@extends('layouts.app_nonavbar')

@section('title', 'SMARTfit - Personalized Recommendation')

@section('content')
<section class="smartfit-recommendation-page">
    <div class="smartfit-recommendation-container">
        <div class="progress-indicator">
            <div class="progress-step completed">
                <span class="step-number">1</span>
                <span class="step-label">Measure</span>
            </div>
            <div class="progress-line"></div>
            <div class="progress-step completed">
                <span class="step-number">2</span>
                <span class="step-label">Style</span>
            </div>
            <div class="progress-line"></div>
            <div class="progress-step active">
                <span class="step-number">3</span>
                <span class="step-label">Result</span>
            </div>
        </div>

        <div class="recommendation-page-header">
            <div class="recommendation-page-icon"><i class="fa-solid fa-magic"></i></div>
            <h1>Your <span>Personalized</span> Recommendations</h1>
            <p>
                Based on your <strong>{{ $bodyType }}</strong> body type and
                <strong>{{ $stylePreference }}</strong> style preference.
            </p>
        </div>

        @if(session('recommendation_updated'))
            <div class="recommendation-page-banner" role="status">
                <i class="fa-regular fa-circle-check"></i>
                <span>{{ session('recommendation_updated') }}</span>
            </div>
        @endif

        @php
            $mainProduct = $systemRecommendation['main_product'];
            $otherProducts = $systemRecommendation['other_products'];
            $mainShopUrl = $mainProduct['shop_url'] ?? null;
        @endphp

        <article class="recommendation-main-card">
            <div class="recommendation-main-image">
                <img
                    src="{{ $mainProduct['main_image'] }}"
                    alt="{{ $mainProduct['name'] }}"
                    onerror="this.onerror=null;this.src='https://placehold.co/900x700/f5f0eb/1b1b1b?text=Image+Not+Found';"
                >
            </div>
            <div class="recommendation-main-info">
                <span class="recommendation-shop"><i class="fa-solid fa-store"></i>{{ $mainProduct['shop'] }}</span>
                <h2>{{ $mainProduct['name'] }}</h2>
                <p>{{ $mainProduct['description'] }}</p>
                @if(!empty($mainProduct['price']))
                    <div class="recommendation-price">{{ $mainProduct['price'] }}</div>
                @endif

                @if(!empty($mainProduct['detail_images']))
                    <div class="recommendation-detail-images">
                        <span>Detail Images</span>
                        <div class="recommendation-thumb-row">
                            @foreach($mainProduct['detail_images'] as $detailImage)
                                <div class="recommendation-thumb">
                                    <img
                                        src="{{ $detailImage }}"
                                        alt="Detail {{ $mainProduct['name'] }}"
                                        onerror="this.onerror=null;this.src='https://placehold.co/240x240/f5f0eb/1b1b1b?text=Detail';"
                                    >
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($mainShopUrl)
                    <a href="{{ $mainShopUrl }}" target="_blank" rel="noopener" class="recommendation-shop-btn">
                        Shop Now <i class="fa-solid fa-arrow-right"></i>
                    </a>
                @else
                    <span class="recommendation-shop-btn" style="opacity:.6; pointer-events:none;">Store Link Unavailable</span>
                @endif
            </div>
        </article>

        <section class="recommendation-also-like" aria-label="You may also like">
            <div class="recommendation-divider-title">
                <span></span>
                <h3>You May Also Like</h3>
                <span></span>
            </div>

            @if(!empty($otherProducts))
                <div class="recommendation-other-grid">
                    @foreach($otherProducts as $other)
                        <article class="recommendation-other-card">
                            <div class="recommendation-other-image">
                                <img
                                    src="{{ $other['main_image'] }}"
                                    alt="{{ $other['name'] }}"
                                    onerror="this.onerror=null;this.src='https://placehold.co/460x360/f5f0eb/1b1b1b?text=Image';"
                                >
                            </div>
                            <h4>{{ $other['name'] }}</h4>
                            @if(!empty($other['price']))
                                <p>{{ $other['price'] }} - {{ $other['shop'] }}</p>
                            @else
                                <p>{{ $other['shop'] }}</p>
                            @endif
                        </article>
                    @endforeach
                </div>
            @else
                <p class="recommendation-empty">More recommendations coming soon!</p>
            @endif
        </section>

        @if(!empty($systemRecommendation['tips']))
            <section class="recommendation-tips" aria-label="Styling tips">
                <h3><i class="fa-solid fa-lightbulb"></i> Styling Tips for {{ $bodyType }}</h3>
                <div class="recommendation-tip-grid">
                    @foreach($systemRecommendation['tips'] as $tip)
                        <article class="recommendation-tip-card">
                            <i class="{{ $tip['icon'] }}"></i>
                            <p>{{ $tip['text'] }}</p>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif

        <div class="recommendation-actions">
            <a href="{{ route('smartfit.result') }}" class="btn-back">
                <i class="fa-solid fa-arrow-left"></i> Back to Style
            </a>
            <a href="{{ route('gallery') }}" class="btn-gallery">
                <i class="fa-solid fa-images"></i> View Gallery
            </a>
            <a href="{{ route('smartfit.start') }}" class="btn-restart">
                <i class="fa-solid fa-undo-alt"></i> Start Over
            </a>
        </div>
    </div>
</section>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/smartfit-recommendation.css') }}">
@endpush
