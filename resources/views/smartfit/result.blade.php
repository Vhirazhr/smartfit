@extends('layouts.app_nonavbar')

@section('title', 'SMARTfit - Your Personalized Recommendations')

@section('content')
<section class="smartfit-result">
    <div class="smartfit-result-container">
        
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
            <a href="{{ route('smartfit.select') }}" class="btn-back">
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
<link rel="stylesheet" href="{{ asset('css/smartfit-result.css') }}">
@endpush

@push('scripts')
<script>
// ==================== DATA DUMMY PRODUCTS (LENGKAP DENGAN DETAIL IMAGES) ====================
const productsDatabase = {
    hourglass: {
        formal: [
            {
                id: 1,
                name: "Elegant Belted Blazer Dress",
                description: "A sophisticated blazer dress with a belt to accentuate your natural waist. Perfect for office meetings and formal events. Made from premium wool-blend fabric that drapes beautifully.",
                main_image: "images/hourglass/formal/H_formal1.jpg",
                detail_images: [
                    "images/hourglass/formal/H_formal1.jpg",
                    "images/hourglass/formal/H_formal1.jpg",
                    "images/hourglass/formal/H_formal1.jpg",
                    "images/hourglass/formal/H_formal1.jpg"
                ],
                price: 89.99,
                shop: "ZARA",
                shopUrl: "https://www.zara.com/"
            },
            {
                id: 2,
                name: "Wrap Midi Dress",
                description: "Flattering wrap dress that hugs your curves in all the right places. Made from premium stretch fabric that moves with you.",
                main_image: "images/hourglass/formal/H_formal2.jpg",
                detail_images: [
                    "images/hourglass/formal/H_formal2.jpg",
                    "images/hourglass/formal/H_formal2.jpg",
                    "images/hourglass/formal/H_formal2.jpg"
                ],
                price: 75.00,
                shop: "MANGO",
                shopUrl: "https://shop.mango.com/"
            },
            {
                id: 3,
                name: "Pencil Skirt Suit Set",
                description: "Classic two-piece suit with a fitted pencil skirt and tailored blazer. Timeless elegance for your professional wardrobe.",
                main_image: "images/hourglass/formal/H_formal3.jpg",
                detail_images: [
                    "images/hourglass/formal/H_formal3.jpg",
                    "images/hourglass/formal/H_formal3.jpg"
                ],
                price: 149.99,
                shop: "H&M",
                shopUrl: "https://www2.hm.com/"
            },
            {
                id: 4,
                name: "Peplum Top with High Waist Pants",
                description: "Peplum top creates beautiful waist definition. Pair with high-waist pants for a complete sophisticated look.",
                main_image: "images/hourglass/formal/H_formal4.jpg",
                detail_images: [
                    "images/hourglass/formal/H_formal4.jpg",
                    "images/hourglass/formal/H_formal4.jpg"
                ],
                price: 65.99,
                shop: "Uniqlo",
                shopUrl: "https://www.uniqlo.com/"
            }
        ],
        casual: [
            {
                id: 5,
                name: "Fitted Ribbed T-Shirt",
                description: "Comfortable fitted t-shirt that follows your natural curves. Perfect for everyday casual wear.",
                main_image: "images/hourglass/casual/H_casual1.jpg",
                detail_images: ["images/hourglass/casual/H_casual1.jpg"],
                price: 24.99,
                shop: "H&M",
                shopUrl: "https://www2.hm.com/"
            },
            {
                id: 6,
                name: "High Waist Straight Jeans",
                description: "High-waist jeans that highlight your waistline. Creates a balanced silhouette.",
                main_image: "images/hourglass/casual/H_casual2.jpg",
                detail_images: ["images/hourglass/casual/H_casual2.jpg"],
                price: 49.99,
                shop: "Levi's",
                shopUrl: "https://www.levi.com/"
            }
        ],
        party: [
            {
                id: 7,
                name: "Sequin Embellished Gown",
                description: "Stunning sequin gown that catches every light. Perfect for special occasions and parties.",
                main_image: "images/hourglass/party/H_party1.jpg",
                detail_images: ["images/hourglass/party/H_party1.jpg"],
                price: 129.99,
                shop: "ASOS",
                shopUrl: "https://www.asos.com/"
            }
        ]
    },
    pear: {
        formal: [
            {
                id: 8,
                name: "A-Line Midi Dress",
                description: "Flattering A-line dress that skims over hips. Perfect for pear-shaped figures.",
                main_image: "images/pear/formal/P_formal1.jpg",
                detail_images: ["images/pear/formal/P_formal1.jpg"],
                price: 79.99,
                shop: "MANGO",
                shopUrl: "https://shop.mango.com/"
            },
            {
                id: 9,
                name: "Off-Shoulder Blouse",
                description: "Draws attention to your upper body. Pair with dark bottoms for balance.",
                main_image: "images/pear/formal/P_formal2.jpg",
                detail_images: ["images/pear/formal/P_formal2.jpg"],
                price: 45.99,
                shop: "ZARA",
                shopUrl: "https://www.zara.com/"
            }
        ],
        casual: [
            {
                id: 10,
                name: "Wide Leg Pants",
                description: "Comfortable wide-leg pants that create a balanced silhouette.",
                main_image: "images/pear/casual/P_casual1.jpg",
                detail_images: ["images/pear/casual/P_casual1.jpg"],
                price: 54.99,
                shop: "Uniqlo",
                shopUrl: "https://www.uniqlo.com/"
            }
        ]
    },
    rectangle: {
        formal: [
            {
                id: 11,
                name: "Peplum Top with Belt",
                description: "Creates the illusion of curves with peplum detail and waist belt.",
                main_image: "images/rectangle/formal/R_formal1.jpg",
                detail_images: ["images/rectangle/formal/R_formal1.jpg"],
                price: 59.99,
                shop: "H&M",
                shopUrl: "https://www2.hm.com/"
            }
        ],
        casual: [
            {
                id: 12,
                name: "Belted Shirt Dress",
                description: "Adds definition to your waist with a stylish belt. Casual yet polished.",
                main_image: "images/rectangle/casual/R_casual1.jpg",
                detail_images: ["images/rectangle/casual/R_casual1.jpg"],
                price: 49.99,
                shop: "ZARA",
                shopUrl: "https://www.zara.com/"
            }
        ]
    },
    inverted_triangle: {
        formal: [
            {
                id: 13,
                name: "V-Neck Blazer",
                description: "Deep V-neckline balances broad shoulders. Structured yet elegant for professional settings.",
                main_image: "images/inverted_triangle/formal/IT_formal1.jpg",
                detail_images: ["images/inverted_triangle/formal/IT_formal1.jpg"],
                price: 89.99,
                shop: "Banana Republic",
                shopUrl: "https://bananarepublic.gap.com/"
            }
        ]
    }
};

// Styling Tips
const stylingTips = {
    hourglass: [
        { icon: "fas fa-tshirt", tip: "Highlight your waist with belted styles and fitted silhouettes" },
        { icon: "fas fa-arrow-up", tip: "Choose V-necklines to elongate your torso" },
        { icon: "fas fa-heart", tip: "Wrap dresses and peplum tops are your best friends" },
        { icon: "fas fa-balance-scale", tip: "Balance proportions with structured shoulders" }
    ],
    pear: [
        { icon: "fas fa-arrow-up", tip: "Draw attention to your upper body with statement tops" },
        { icon: "fas fa-tshirt", tip: "A-line skirts and wide-leg pants flatter your shape" },
        { icon: "fas fa-arrow-down", tip: "Choose darker colors on bottom, lighter on top" }
    ],
    rectangle: [
        { icon: "fas fa-belt", tip: "Create curves with belted styles and peplum details" },
        { icon: "fas fa-circle", tip: "Add volume with ruffles and layered pieces" },
        { icon: "fas fa-arrows-alt-h", tip: "Define your waist with strategic color blocking" }
    ],
    inverted_triangle: [
        { icon: "fas fa-arrow-down", tip: "Balance broad shoulders with A-line skirts" },
        { icon: "fas fa-tshirt", tip: "Raglan sleeves and deep V-necks soften the upper body" },
        { icon: "fas fa-star", tip: "Wide-leg pants create proportional balance" }
    ]
};

// Get data dari session atau default
let currentBodyType = "{{ strtolower(session('body_type', 'Hourglass')) }}";
let currentStyle = "{{ strtolower(session('style_preference', 'Formal')) }}";
let currentProductIndex = 0;

// Get products array
function getProducts() {
    if (productsDatabase[currentBodyType] && productsDatabase[currentBodyType][currentStyle]) {
        return productsDatabase[currentBodyType][currentStyle];
    }
    return [];
}

// Render main product detail dengan detail images
function renderMainProduct() {
    const container = document.getElementById('productDetailContainer');
    const products = getProducts();
    
    if (products.length === 0) {
        container.innerHTML = `
            <div class="empty-state">
                <i class="fas fa-tshirt"></i>
                <p>No recommendations yet for this combination. Check back soon!</p>
            </div>
        `;
        return;
    }
    
    const product = products[currentProductIndex];
    const currentMainImage = product.main_image;
    
    container.innerHTML = `
        <div class="product-detail">
            <div class="product-detail-image">
                <img id="mainProductImage" src="{{ asset('') }}${currentMainImage}" 
                     alt="${product.name}"
                     onerror="this.src='https://placehold.co/600x800/f5f0ed/1B1B1B?text=Image+Not+Found'">
            </div>
            <div class="product-detail-info">
                <div class="product-detail-shop">
                    <i class="fas fa-store"></i>
                    <span>${product.shop}</span>
                </div>
                <h2 class="product-detail-name">${product.name}</h2>
                <p class="product-detail-description">${product.description}</p>
                <div class="product-detail-price">$${product.price}</div>
                
                <!-- DETAIL IMAGES (thumbnail di antara harga dan tombol) -->
                <div class="product-detail-images">
                    <span class="detail-label">DETAIL IMAGES</span>
                    <div class="detail-thumbnails" id="detailThumbnails">
                        ${product.detail_images.map((img, idx) => `
                            <div class="detail-thumb ${idx === 0 ? 'active' : ''}" onclick="changeMainImage('${img}', this)">
                                <img src="{{ asset('') }}${img}" alt="Detail ${idx + 1}">
                            </div>
                        `).join('')}
                    </div>
                </div>
                
                <a href="${product.shopUrl}" target="_blank" class="product-detail-btn">
                    Shop Now <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    `;
}

// Function untuk ganti gambar utama saat thumbnail diklik
function changeMainImage(imageUrl, element) {
    const mainImage = document.getElementById('mainProductImage');
    if (mainImage) {
        mainImage.src = "{{ asset('') }}" + imageUrl;
    }
    
    // Update active class pada thumbnail
    document.querySelectorAll('.detail-thumb').forEach(thumb => {
        thumb.classList.remove('active');
    });
    element.classList.add('active');
}

// Render You May Also Like
function renderAlsoLike() {
    const container = document.getElementById('alsoLikeGrid');
    const products = getProducts();
    
    // Filter out current product
    const otherProducts = products.filter((_, index) => index !== currentProductIndex);
    
    if (otherProducts.length === 0) {
        container.innerHTML = `
            <div class="also-like-empty">
                <p>More recommendations coming soon!</p>
            </div>
        `;
        return;
    }
    
    container.innerHTML = otherProducts.map(product => `
        <div class="also-like-card" onclick="selectProduct(${product.id})">
            <div class="also-like-image">
                <img src="{{ asset('') }}${product.main_image}" 
                     alt="${product.name}"
                     onerror="this.src='https://placehold.co/300x400/f5f0ed/1B1B1B?text=Image+Not+Found'">
            </div>
            <div class="also-like-info">
                <h4>${product.name}</h4>
                <p>$${product.price}</p>
                <span class="also-like-shop">${product.shop}</span>
            </div>
        </div>
    `).join('');
}

// Select product from You May Also Like
function selectProduct(productId) {
    const products = getProducts();
    const newIndex = products.findIndex(p => p.id === productId);
    if (newIndex !== -1) {
        currentProductIndex = newIndex;
        renderMainProduct();
        renderAlsoLike();
        // Scroll ke atas sedikit
        window.scrollTo({ top: 350, behavior: 'smooth' });
    }
}

// Render tips
function renderTips() {
    const tipsGrid = document.getElementById('tipsGrid');
    const tips = stylingTips[currentBodyType] || stylingTips.hourglass;
    
    tipsGrid.innerHTML = tips.map(tip => `
        <div class="tip-card">
            <i class="${tip.icon}"></i>
            <p>${tip.tip}</p>
        </div>
    `).join('');
}

// Initial render
renderMainProduct();
renderAlsoLike();
renderTips();

// Make functions global
window.changeMainImage = changeMainImage;
window.selectProduct = selectProduct;
</script>
@endpush