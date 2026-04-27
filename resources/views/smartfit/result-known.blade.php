@extends('layouts.app_nonavbar')

@section('title', 'SMARTfit - Your Personalized Recommendations')

@section('content')
<section class="result-known">
    <div class="result-known-container">
        
        <!-- Progress Indicator -->
        <div class="progress-indicator">
            <div class="progress-step completed">
                <span class="step-number">1</span>
                <span class="step-label">Identify</span>
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

        <!-- Result Header -->
        <div class="result-header">
            <div class="result-icon">
                <i class="fas fa-magic"></i>
            </div>
            <h1 class="result-title">Your <span>Personalized</span> Recommendations</h1>
            <p class="result-subtitle">
                Based on your <strong id="displayBodyType">{{ session('body_type', 'Hourglass') }}</strong> body type 
                and <strong id="displayStyle">{{ session('style_preference', 'Formal') }}</strong> style preference
            </p>
        </div>

        <!-- MAIN PRODUCT DETAIL -->
        <div class="product-detail-container" id="productDetailContainer">
            <!-- Akan di-load via JavaScript -->
        </div>

        <!-- YOU MAY ALSO LIKE SECTION -->
        <div class="also-like-section">
            <div class="also-like-header">
                <span class="also-like-line"></span>
                <h3>You May Also Like</h3>
                <span class="also-like-line"></span>
            </div>
            <div class="also-like-grid" id="alsoLikeGrid">
                <!-- Rekomendasi produk lain akan di-load -->
            </div>
        </div>

        <!-- Styling Tips Section -->
        <div class="tips-section">
            <h3><i class="fas fa-lightbulb"></i> Styling Tips for <span id="tipsBodyType">{{ session('body_type', 'Hourglass') }}</span></h3>
            <div class="tips-grid" id="tipsGrid">
                <!-- Tips akan di-load via JavaScript -->
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="result-actions">
            <a href="{{ route('known.select') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Back to Style
            </a>
            <a href="{{ route('gallery') }}" class="btn-gallery">
                <i class="fas fa-images"></i> View Gallery
            </a>
            <a href="{{ route('smartfit.start') }}" class="btn-restart">
                <i class="fas fa-undo-alt"></i> Start Over
            </a>
        </div>
        
    </div>
</section>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/result-known.css') }}">
@endpush

@push('scripts')
<script id="knownRecommendedItemsData" type="application/json">{!! json_encode($recommendedItems ?? [], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}</script>
<script id="knownStylingTipsData" type="application/json">{!! json_encode($stylingTips ?? [], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}</script>
<script>
const recommendedItems = JSON.parse(document.getElementById('knownRecommendedItemsData').textContent || '[]');
const stylingTips = JSON.parse(document.getElementById('knownStylingTipsData').textContent || '[]');
let currentProductIndex = 0;

function getProducts() {
    return Array.isArray(recommendedItems) ? recommendedItems : [];
}

function renderMainProduct() {
    const container = document.getElementById('productDetailContainer');
    const products = getProducts();
    
    if (products.length === 0) {
        container.innerHTML = `<div class="empty-state"><i class="fas fa-tshirt"></i><p>No recommendations yet.</p></div>`;
        return;
    }
    
    const product = products[currentProductIndex];
    const detailImages = Array.isArray(product.detail_images) && product.detail_images.length > 0
        ? product.detail_images
        : [product.main_image];
    const hasShopLink = Boolean(product.shopUrl);
    const storeList = Array.isArray(product.stores)
        ? product.stores.map((store) => store.name).filter(Boolean).join(', ')
        : '';
    const priceMarkup = product.price !== undefined && product.price !== null
        ? `<div class="product-detail-price">$${Number(product.price).toFixed(2)}</div>`
        : '';
    const buttonMarkup = hasShopLink
        ? `<a href="${product.shopUrl}" target="_blank" rel="noopener" class="product-detail-btn">Shop Now <i class="fas fa-arrow-right"></i></a>`
        : '<span class="product-detail-btn" style="opacity:.6; pointer-events:none;">Store Link Unavailable</span>';
    
    container.innerHTML = `
        <div class="product-detail">
            <div class="product-detail-image">
                <img id="mainProductImage" src="${product.main_image}" 
                     alt="${product.name}"
                     onerror="this.src='https://placehold.co/600x800/f5f0ed/1B1B1B?text=Image+Not+Found'">
            </div>
            <div class="product-detail-info">
                <div class="product-detail-shop"><i class="fas fa-store"></i><span>${product.shop}${storeList ? ` • ${storeList}` : ''}</span></div>
                <h2 class="product-detail-name">${product.name}</h2>
                <p class="product-detail-description">${product.description}</p>
                ${priceMarkup}
                <div class="product-detail-images">
                    <span class="detail-label">DETAIL IMAGES</span>
                    <div class="detail-thumbnails">
                        ${detailImages.map((img, idx) => {
                            const safeImg = String(img).replace(/'/g, "\\'");
                            return `
                                <div class="detail-thumb ${idx === 0 ? 'active' : ''}" onclick="changeMainImage('${safeImg}', this)">
                                    <img src="${img}" alt="Detail ${idx + 1}">
                                </div>
                            `;
                        }).join('')}
                    </div>
                </div>
                ${buttonMarkup}
            </div>
        </div>
    `;
}

function changeMainImage(imageUrl, element) {
    const mainImage = document.getElementById('mainProductImage');
    if (mainImage) mainImage.src = imageUrl;
    document.querySelectorAll('.detail-thumb').forEach(thumb => thumb.classList.remove('active'));
    element.classList.add('active');
}

function renderAlsoLike() {
    const container = document.getElementById('alsoLikeGrid');
    const products = getProducts();
    const otherProducts = products.filter((_, index) => index !== currentProductIndex);
    
    if (otherProducts.length === 0) {
        container.innerHTML = `<div class="also-like-empty"><p>More recommendations coming soon!</p></div>`;
        return;
    }
    
    container.innerHTML = otherProducts.map(product => `
        <div class="also-like-card" onclick="selectProduct(${product.id})">
            <div class="also-like-image"><img src="${product.main_image}" alt="${product.name}" onerror="this.src='https://placehold.co/600x800/f5f0ed/1B1B1B?text=Image+Not+Found'"></div>
            <div class="also-like-info">
                <h4>${product.name}</h4>
                ${product.price !== undefined && product.price !== null ? `<p>$${Number(product.price).toFixed(2)}</p>` : ''}
                <span class="also-like-shop">${product.shop || 'Marketplace'}</span>
            </div>
        </div>
    `).join('');
}

function selectProduct(productId) {
    const products = getProducts();
    const newIndex = products.findIndex(p => p.id === productId);
    if (newIndex !== -1) {
        currentProductIndex = newIndex;
        renderMainProduct();
        renderAlsoLike();
        window.scrollTo({ top: 350, behavior: 'smooth' });
    }
}

function renderTips() {
    const tipsGrid = document.getElementById('tipsGrid');
    const tips = Array.isArray(stylingTips) ? stylingTips : [];

    if (tips.length === 0) {
        tipsGrid.innerHTML = '<div class="tip-card"><i class="fas fa-lightbulb"></i><p>Styling tips will appear once recommendations are available.</p></div>';
        return;
    }

    tipsGrid.innerHTML = tips.map((tip) => `<div class="tip-card"><i class="${tip.icon}"></i><p>${tip.tip}</p></div>`).join('');
}

renderMainProduct();
renderAlsoLike();
renderTips();

window.changeMainImage = changeMainImage;
window.selectProduct = selectProduct;
</script>
@endpush
