@extends('layouts.app')

@section('title', 'Fashion Gallery - Find Your Style by Body Type')

@section('content')
<section class="gallery-page-section">
    <div class="gallery-container">
        <aside class="gallery-sidebar">
            <div class="sidebar-header">
                <h3>Body Type</h3>
                <i class="fa-regular fa-compass"></i>
            </div>

            <div class="body-type-filters" id="bodyTypeFilters">
                @forelse($bodyFilters as $bodyFilter)
                    <button class="body-filter-btn {{ $loop->first ? 'active' : '' }}" data-body="{{ $bodyFilter['key'] }}">
                        <span>{{ $bodyFilter['label'] }}</span>
                        <span class="count">{{ $bodyFilter['count'] }}</span>
                    </button>
                @empty
                    <p class="gallery-empty-sidebar">No body type data yet.</p>
                @endforelse
            </div>

            <div class="style-filters-wrapper">
                <h4>Style Category</h4>
                <div class="style-filters" id="categoryFilters">
                    <button class="style-filter-btn active" data-category="all">All</button>
                    @foreach($categoryFilters as $categoryFilter)
                        <button class="style-filter-btn" data-category="{{ $categoryFilter['slug'] }}">{{ $categoryFilter['name'] }}</button>
                    @endforeach
                </div>
            </div>
        </aside>

        <main class="gallery-main">
            <div class="gallery-header">
                <h2 id="activeBodyTitle">Gallery</h2>
                <p id="activeBodyDesc">Browse dynamic recommendations by body type and category.</p>
            </div>

            <div class="gallery-masonry" id="galleryGrid"></div>

            <div class="load-more-container">
                <button class="load-more-btn" id="loadMoreBtn">
                    Load More
                    <i class="fa-solid fa-arrow-down"></i>
                </button>
            </div>
        </main>
    </div>
</section>

<div id="lightboxModal" class="lightbox-modal">
    <span class="lightbox-close">&times;</span>
    <button class="lightbox-prev"><i class="fa-solid fa-chevron-left"></i></button>
    <button class="lightbox-next"><i class="fa-solid fa-chevron-right"></i></button>
    <div class="lightbox-content">
        <img id="lightboxImg" src="" alt="Fashion item preview">
        <div class="lightbox-info">
            <h3 id="lightboxTitle"></h3>
            <p id="lightboxBody"></p>
            <p id="lightboxCategory"></p>
            <a id="lightboxPurchase" class="lightbox-purchase" target="_blank" rel="noopener">Buy this item</a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js"></script>
<script src="https://unpkg.com/imagesloaded@5/imagesloaded.pkgd.min.js"></script>
<script id="galleryItemsData" type="application/json">@json($galleryItems)</script>
<script id="bodyFiltersData" type="application/json">@json($bodyFilters)</script>
<script id="bodyDescriptionsData" type="application/json">@json($bodyDescriptions)</script>

<script>
    const galleryItems = JSON.parse(document.getElementById('galleryItemsData').textContent || '[]');
    const bodyFilters = JSON.parse(document.getElementById('bodyFiltersData').textContent || '[]');
    const bodyDescriptions = JSON.parse(document.getElementById('bodyDescriptionsData').textContent || '{}');

    const bodyLabelMap = bodyFilters.reduce((acc, item) => {
        acc[item.key] = item.label;
        return acc;
    }, {});

    let currentBody = bodyFilters.length ? bodyFilters[0].key : 'all';
    let currentCategory = 'all';
    let currentPage = 1;
    const itemsPerPage = 12;
    let masonryGrid = null;

    let currentLightboxItems = [];
    let currentLightboxIndex = 0;

    function getFilteredItems() {
        return galleryItems.filter((item) => {
            const bodyMatch = currentBody === 'all' || item.body_type === currentBody;
            const categoryMatch = currentCategory === 'all' || item.category_slug === currentCategory;

            return bodyMatch && categoryMatch;
        });
    }

    function renderHeader() {
        const titleEl = document.getElementById('activeBodyTitle');
        const descEl = document.getElementById('activeBodyDesc');

        if (currentBody === 'all') {
            titleEl.innerText = 'All Body Types';
            descEl.innerText = 'Browse all active items from live gallery data.';
            return;
        }

        titleEl.innerText = bodyLabelMap[currentBody] || currentBody;
        descEl.innerText = bodyDescriptions[currentBody] || 'Style recommendations based on your selected body type.';
    }

    function renderGallery() {
        const grid = document.getElementById('galleryGrid');
        const filteredItems = getFilteredItems();
        const visibleItems = filteredItems.slice(0, currentPage * itemsPerPage);

        grid.innerHTML = '';

        if (visibleItems.length === 0) {
            grid.innerHTML = '<div class="empty-gallery-state"><i class="fa-regular fa-image"></i><p>No items available for this filter.</p></div>';

            const loadMoreBtn = document.getElementById('loadMoreBtn');
            loadMoreBtn.style.display = 'none';

            if (masonryGrid) {
                masonryGrid.destroy();
                masonryGrid = null;
            }

            return;
        }

        visibleItems.forEach((item) => {
            const card = document.createElement('div');
            card.className = 'masonry-item';
            card.innerHTML = `
                <div class="masonry-card">
                    <img src="${item.image_url}"
                         alt="${item.title}"
                         loading="lazy"
                         onerror="this.src='https://placehold.co/600x800/f5f0ed/1B1B1B?text=Image+Not+Found'">
                    <div class="card-overlay">
                        <div class="card-actions">
                            <button class="save-btn" onclick="saveImage(${item.id})">
                                <i class="fa-solid fa-bookmark"></i>
                            </button>
                            <button class="expand-btn" onclick="openLightbox(${item.id})">
                                <i class="fa-solid fa-up-right-and-down-left-from-center"></i>
                            </button>
                        </div>
                        <div class="card-tags">
                            <span class="tag-style">${item.category_name || 'Category'}</span>
                        </div>
                        ${item.purchase_link ? `<a href="${item.purchase_link}" target="_blank" rel="noopener" class="card-buy-link" onclick="event.stopPropagation();">Buy</a>` : ''}
                    </div>
                </div>
            `;
            grid.appendChild(card);
        });

        if (masonryGrid) {
            masonryGrid.destroy();
            masonryGrid = null;
        }

        imagesLoaded(grid, function () {
            masonryGrid = new Masonry(grid, {
                itemSelector: '.masonry-item',
                columnWidth: '.masonry-item',
                percentPosition: true,
                gutter: 20,
            });
        });

        const loadMoreBtn = document.getElementById('loadMoreBtn');
        loadMoreBtn.style.display = visibleItems.length >= filteredItems.length ? 'none' : 'inline-flex';
    }

    function loadMore() {
        currentPage += 1;
        renderGallery();
    }

    function filterByBody(bodyType) {
        currentBody = bodyType;
        currentCategory = 'all';
        currentPage = 1;

        document.querySelectorAll('.body-filter-btn').forEach((btn) => {
            btn.classList.toggle('active', btn.dataset.body === bodyType);
        });

        document.querySelectorAll('.style-filter-btn').forEach((btn) => {
            btn.classList.toggle('active', btn.dataset.category === 'all');
        });

        renderHeader();
        renderGallery();
    }

    function filterByCategory(categorySlug) {
        currentCategory = categorySlug;
        currentPage = 1;

        document.querySelectorAll('.style-filter-btn').forEach((btn) => {
            btn.classList.toggle('active', btn.dataset.category === categorySlug);
        });

        renderGallery();
    }

    function saveImage() {
        alert('Item saved to your inspiration list.');
    }

    function openLightbox(itemId) {
        currentLightboxItems = getFilteredItems();
        currentLightboxIndex = currentLightboxItems.findIndex((item) => item.id === itemId);

        updateLightbox();
        document.getElementById('lightboxModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function updateLightbox() {
        const item = currentLightboxItems[currentLightboxIndex];
        if (!item) {
            return;
        }

        document.getElementById('lightboxImg').src = item.image_url;
        document.getElementById('lightboxTitle').innerText = item.title;
        document.getElementById('lightboxBody').innerHTML = `<strong>Body Type:</strong> ${item.body_label}`;
        document.getElementById('lightboxCategory').innerHTML = `<strong>Category:</strong> ${item.category_name || '-'}`;

        const purchaseLink = document.getElementById('lightboxPurchase');
        if (item.purchase_link) {
            purchaseLink.href = item.purchase_link;
            purchaseLink.style.display = 'inline-flex';
        } else {
            purchaseLink.style.display = 'none';
            purchaseLink.removeAttribute('href');
        }
    }

    function closeLightbox() {
        document.getElementById('lightboxModal').style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    function prevImage() {
        if (!currentLightboxItems.length) {
            return;
        }

        currentLightboxIndex = (currentLightboxIndex - 1 + currentLightboxItems.length) % currentLightboxItems.length;
        updateLightbox();
    }

    function nextImage() {
        if (!currentLightboxItems.length) {
            return;
        }

        currentLightboxIndex = (currentLightboxIndex + 1) % currentLightboxItems.length;
        updateLightbox();
    }

    document.querySelectorAll('.body-filter-btn').forEach((btn) => {
        btn.addEventListener('click', () => filterByBody(btn.dataset.body));
    });

    document.querySelectorAll('.style-filter-btn').forEach((btn) => {
        btn.addEventListener('click', () => filterByCategory(btn.dataset.category));
    });

    document.getElementById('loadMoreBtn').addEventListener('click', loadMore);
    document.querySelector('.lightbox-close').addEventListener('click', closeLightbox);
    document.querySelector('.lightbox-prev').addEventListener('click', prevImage);
    document.querySelector('.lightbox-next').addEventListener('click', nextImage);

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeLightbox();
        }
        if (event.key === 'ArrowLeft') {
            prevImage();
        }
        if (event.key === 'ArrowRight') {
            nextImage();
        }
    });

    renderHeader();
    renderGallery();

    window.saveImage = saveImage;
    window.openLightbox = openLightbox;
</script>
@endpush
