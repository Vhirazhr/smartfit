@extends('layouts.app')

@section('title', 'Fashion Gallery - Find Your Style by Body Type')

@section('content')
<section class="gallery-page-section">
    <div class="gallery-container">
        
        <!-- SIDEBAR KIRI - Filter Body Type -->
        <aside class="gallery-sidebar">
            <div class="sidebar-header">
                <h3>Body Type</h3>
                <i class="fa-regular fa-compass"></i>
            </div>
            
            <button class="body-filter-btn active" data-body="hourglass">
    <i class="fa-solid fa-hourglass-half"></i>
    <span>Hourglass</span>
    <span class="count">12</span>
</button>

<button class="body-filter-btn" data-body="pear">
    <i class="fa-solid fa-leaf"></i>
    <span>Pear</span>
    <span class="count">8</span>
</button>

<button class="body-filter-btn" data-body="apple">
    <i class="fa-solid fa-apple-whole"></i>
    <span>Apple</span>
    <span class="count">6</span>
</button>

<button class="body-filter-btn" data-body="rectangle">
    <i class="fa-solid fa-vector-square"></i>
    <span>Rectangle</span>
    <span class="count">9</span>
</button>

<button class="body-filter-btn" data-body="inverted_triangle">
    <i class="fa-solid fa-caret-up"></i>
    <span>Inverted Triangle</span>
    <span class="count">5</span>
</button>
            
            <!-- Style Category Filter -->
            <div class="style-filters-wrapper">
                <h4>Style Category</h4>
                <div class="style-filters">
                    <button class="style-filter-btn active" data-style="all">All</button>
                    <button class="style-filter-btn" data-style="formal">Formal</button>
                    <button class="style-filter-btn" data-style="casual">Casual</button>
                    <button class="style-filter-btn" data-style="party">Party</button>
                    <button class="style-filter-btn" data-style="office">Office</button>
                    <button class="style-filter-btn" data-style="sporty">Sporty</button>
                </div>
            </div>
        </aside>
        
        <!-- MAIN CONTENT - Pinterest Style Masonry Grid -->
        <main class="gallery-main">
            <div class="gallery-header">
                <h2 id="activeBodyTitle">Hourglass</h2>
                <p id="activeBodyDesc">Outfit recommendations for hourglass body shape. Highlight your balanced curves!</p>
            </div>
            
            <!-- Masonry Grid seperti Pinterest -->
            <div class="gallery-masonry" id="galleryGrid">
                <!-- Gambar akan di-load via JavaScript -->
            </div>
            
            <!-- Load More Button -->
            <div class="load-more-container">
                <button class="load-more-btn" id="loadMoreBtn">
                    Load More 
                    <i class="fa-solid fa-arrow-down"></i>
                </button>
            </div>
        </main>
    </div>
</section>

<!-- Lightbox Modal untuk Preview Gambar -->
<div id="lightboxModal" class="lightbox-modal">
    <span class="lightbox-close">&times;</span>
    <button class="lightbox-prev"><i class="fa-solid fa-chevron-left"></i></button>
    <button class="lightbox-next"><i class="fa-solid fa-chevron-right"></i></button>
    <div class="lightbox-content">
        <img id="lightboxImg" src="">
        <div class="lightbox-info">
            <h3 id="lightboxTitle"></h3>
            <p id="lightboxBody"></p>
            <p id="lightboxStyle"></p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js"></script>
<script src="https://unpkg.com/imagesloaded@5/imagesloaded.pkgd.min.js"></script>

<script>
// ==================== DATA GAMBAR (DUMMY/STATIC) ====================

const imageDatabase = {
    hourglass: {
        formal: [
            { id: 1, title: "Elegant Blazer Dress", path: "images/hourglass/formal/H_formal1.jpg", style: "formal", body: "hourglass" },
            { id: 2, title: "Belted Wrap Dress", path: "images/hourglass/formal/H_formal2.jpg", style: "formal", body: "hourglass" },
            { id: 3, title: "Pencil Skirt Suit", path: "images/hourglass/formal/H_formal3.jpg", style: "formal", body: "hourglass" },
            { id: 4, title: "Peplum Top Set", path: "images/hourglass/formal/H_formal4.jpg", style: "formal", body: "hourglass" }
        ],
        casual: [
            { id: 5, title: "Fitted T-Shirt", path: "images/hourglass/casual/H_casual1.jpg", style: "casual", body: "hourglass" },
            { id: 6, title: "High Waist Jeans", path: "images/hourglass/casual/H_casual2.jpg", style: "casual", body: "hourglass" },
            { id: 7, title: "Bodycon Dress", path: "images/hourglass/casual/H_casual3.jpg", style: "casual", body: "hourglass" }
        ],
        party: [
            { id: 8, title: "Sequin Gown", path: "images/hourglass/party/H_party1.jpg", style: "party", body: "hourglass" },
            { id: 9, title: "Mermaid Dress", path: "images/hourglass/party/H_party2.jpg", style: "party", body: "hourglass" }
        ],
        office: [
            { id: 10, title: "Tailored Pantsuit", path: "images/hourglass/office/H_office1.jpg", style: "office", body: "hourglass" }
        ]
    },
    pear: {
        formal: [
            { id: 11, title: "A-Line Dress", path: "images/pear/formal/P_formal1.jpg", style: "formal", body: "pear" },
            { id: 12, title: "Off-Shoulder Top", path: "images/pear/formal/P_formal2.jpg", style: "formal", body: "pear" }
        ],
        casual: [
            { id: 13, title: "Wide Leg Pants", path: "images/pear/casual/P_casual1.jpg", style: "casual", body: "pear" }
        ]
    },
    apple: {
        formal: [
            { id: 14, title: "Empire Waist Dress", path: "images/apple/formal/A_formal1.jpg", style: "formal", body: "apple" }
        ],
        casual: [
            { id: 15, title: "V-Neck Top", path: "images/apple/casual/A_casual1.jpg", style: "casual", body: "apple" }
        ]
    },
    rectangle: {
        formal: [
            { id: 16, title: "Peplum Top", path: "images/rectangle/formal/R_formal1.jpg", style: "formal", body: "rectangle" }
        ],
        casual: [
            { id: 17, title: "Belted Shirt Dress", path: "images/rectangle/casual/R_casual1.jpg", style: "casual", body: "rectangle" }
        ]
    },
    inverted_triangle: {
        formal: [
            { id: 18, title: "V-Neck Blazer", path: "images/inverted_triangle/formal/IT_formal1.jpg", style: "formal", body: "inverted_triangle" }
        ]
    }
};

// Body type descriptions
const bodyDescriptions = {
    hourglass: "Outfit recommendations for hourglass body shape. Highlight your balanced curves with fitted styles!",
    pear: "Balance your silhouette with outfits that accentuate your upper body.",
    apple: "Create definition at your waist with empire lines and V-necks.",
    rectangle: "Create curves with belted styles and peplum details.",
    inverted_triangle: "Balance broad shoulders with A-line skirts and wide-leg pants."
};

// State
let currentBody = "hourglass";
let currentStyle = "all";
let currentPage = 1;
const itemsPerPage = 12;
let masonryGrid = null;

// Get filtered images
function getFilteredImages() {
    let images = [];
    
    if (imageDatabase[currentBody]) {
        for (const [style, styleImages] of Object.entries(imageDatabase[currentBody])) {
            if (currentStyle === "all" || style === currentStyle) {
                images.push(...styleImages);
            }
        }
    }
    
    return images;
}

// Render masonry grid
function renderGallery() {
    const grid = document.getElementById('galleryGrid');
    const filteredImages = getFilteredImages();
    const start = 0;
    const end = currentPage * itemsPerPage;
    const imagesToShow = filteredImages.slice(start, end);
    
    if (currentPage === 1) {
        grid.innerHTML = '';
    }
    
    imagesToShow.forEach(img => {
        const item = document.createElement('div');
        item.className = 'masonry-item';
        item.setAttribute('data-id', img.id);
        item.innerHTML = `
            <div class="masonry-card">
                <img src="{{ asset('') }}${img.path}" 
                     alt="${img.title}"
                     loading="lazy"
                     onerror="this.src='https://placehold.co/600x800/f5f0ed/1B1B1B?text=Image+Not+Found'">
                <div class="card-overlay">
                    <div class="card-actions">
                        <button class="save-btn" onclick="saveImage(${img.id})">
                            <i class="fa-solid fa-bookmark"></i>
                        </button>
                        <button class="expand-btn" onclick="openLightbox(${img.id})">
                            <i class="fa-solid fa-up-right-and-down-left-from-center"></i>
                        </button>
                    </div>
                    <div class="card-tags">
                        <span class="tag-style">${img.style}</span>
                    </div>
                </div>
            </div>
        `;
        grid.appendChild(item);
    });
    
    // Initialize or reload masonry
    if (masonryGrid) {
        masonryGrid.reloadItems();
        masonryGrid.layout();
    } else {
        imagesLoaded(grid, function() {
            masonryGrid = new Masonry(grid, {
                itemSelector: '.masonry-item',
                columnWidth: '.masonry-item',
                percentPosition: true,
                gutter: 20
            });
        });
    }
    
    // Update load more button
    const loadMoreBtn = document.getElementById('loadMoreBtn');
    if (imagesToShow.length >= filteredImages.length) {
        loadMoreBtn.style.display = 'none';
    } else {
        loadMoreBtn.style.display = 'inline-flex';
    }
}

// Load more function
function loadMore() {
    currentPage++;
    renderGallery();
}

// Filter by body type
function filterByBody(bodyType) {
    currentBody = bodyType;
    currentStyle = "all";
    currentPage = 1;
    
    // Update active button
    document.querySelectorAll('.body-filter-btn').forEach(btn => {
        btn.classList.remove('active');
        if (btn.dataset.body === bodyType) {
            btn.classList.add('active');
        }
    });
    
    // Update style filters active
    document.querySelectorAll('.style-filter-btn').forEach(btn => {
        btn.classList.remove('active');
        if (btn.dataset.style === "all") {
            btn.classList.add('active');
        }
    });
    
    // Update header
    let displayName = bodyType.split('_').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
    document.getElementById('activeBodyTitle').innerText = displayName;
    document.getElementById('activeBodyDesc').innerText = bodyDescriptions[bodyType];
    
    renderGallery();
}

// Filter by style category
function filterByStyle(style) {
    currentStyle = style;
    currentPage = 1;
    
    document.querySelectorAll('.style-filter-btn').forEach(btn => {
        btn.classList.remove('active');
        if (btn.dataset.style === style) {
            btn.classList.add('active');
        }
    });
    
    renderGallery();
}

// Save image (bookmark)
function saveImage(imageId) {
    alert('✨ Image saved to your inspiration board!');
}

// Lightbox functions
let currentLightboxImages = [];
let currentLightboxIndex = 0;

function openLightbox(imageId) {
    const filteredImages = getFilteredImages();
    currentLightboxImages = filteredImages;
    currentLightboxIndex = currentLightboxImages.findIndex(img => img.id === imageId);
    
    updateLightbox();
    document.getElementById('lightboxModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function updateLightbox() {
    const img = currentLightboxImages[currentLightboxIndex];
    if (img) {
        document.getElementById('lightboxImg').src = "{{ asset('') }}" + img.path;
        document.getElementById('lightboxTitle').innerText = img.title;
        document.getElementById('lightboxBody').innerHTML = `<strong>Body Type:</strong> ${img.body}`;
        document.getElementById('lightboxStyle').innerHTML = `<strong>Style:</strong> ${img.style}`;
    }
}

function closeLightbox() {
    document.getElementById('lightboxModal').style.display = 'none';
    document.body.style.overflow = 'auto';
}

function prevImage() {
    currentLightboxIndex--;
    if (currentLightboxIndex < 0) {
        currentLightboxIndex = currentLightboxImages.length - 1;
    }
    updateLightbox();
}

function nextImage() {
    currentLightboxIndex++;
    if (currentLightboxIndex >= currentLightboxImages.length) {
        currentLightboxIndex = 0;
    }
    updateLightbox();
}

// Event Listeners
document.querySelectorAll('.body-filter-btn').forEach(btn => {
    btn.addEventListener('click', () => filterByBody(btn.dataset.body));
});

document.querySelectorAll('.style-filter-btn').forEach(btn => {
    btn.addEventListener('click', () => filterByStyle(btn.dataset.style));
});

document.getElementById('loadMoreBtn').addEventListener('click', loadMore);
document.querySelector('.lightbox-close').addEventListener('click', closeLightbox);
document.querySelector('.lightbox-prev').addEventListener('click', prevImage);
document.querySelector('.lightbox-next').addEventListener('click', nextImage);

// Close lightbox with ESC
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeLightbox();
    if (e.key === 'ArrowLeft') prevImage();
    if (e.key === 'ArrowRight') nextImage();
});

// Initial render
renderGallery();
</script>
@endpush