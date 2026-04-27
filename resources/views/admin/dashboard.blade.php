@extends('layouts.app_nonavbar')

@section('title', 'SMARTfit - Admin Dashboard')

@section('content')
<div class="admin-dashboard">   
    <div class="dashboard-container">
        <div class="dashboard-header">
            <div class="header-left">
                <h1>Fashion Management</h1>
                <p>Kelola koleksi fashion, rekomendasi, dan toko penyedia</p>
            </div>
            <div class="header-right">
                <div class="admin-profile">
                    <div class="admin-info">
                        <span class="admin-name">Admin User</span>
                        <span class="admin-role">Administrator</span>
                    </div>
                    <div class="admin-avatar">
                        <i class="fas fa-user-shield"></i>
                    </div>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div style="margin: 0 0 16px; padding: 12px 14px; border-radius: 12px; background: #e8f8ef; color: #1f7a4c; border: 1px solid #cbe9d7;">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div style="margin: 0 0 16px; padding: 12px 14px; border-radius: 12px; background: #fdecec; color: #8a2f2f; border: 1px solid #f5cfcf;">
                <strong>Periksa kembali input Anda:</strong>
                <ul style="margin: 8px 0 0 16px; padding: 0;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div style="display:flex; gap:10px; flex-wrap:wrap; margin: 0 0 18px;">
            <a href="{{ route('admin.fashion-categories.index') }}" class="btn-submit" style="text-decoration:none; width:auto; padding:10px 14px;">
                <i class="fas fa-tags"></i> Kelola Kategori
            </a>
            <a href="{{ route('admin.fashion-items.index') }}" class="btn-submit" style="text-decoration:none; width:auto; padding:10px 14px;">
                <i class="fas fa-tshirt"></i> Kelola Fashion Items
            </a>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-tshirt"></i>
                </div>
                <div class="stat-info">
                    <h3 id="totalItemsCount">{{ $stats['total_items'] ?? 0 }}</h3>
                    <p>Total Fashion</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-tag"></i>
                </div>
                <div class="stat-info">
                    <h3>9</h3>
                    <p>Body Types</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-store"></i>
                </div>
                <div class="stat-info">
                    <h3 id="totalStoresCount">{{ $stats['total_stores'] ?? 0 }}</h3>
                    <p>Stores</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-info">
                    <h3>81.9%</h3>
                    <p>Accuracy</p>
                </div>
            </div>
        </div>

        <div class="two-columns">
            <div class="form-panel">
                <div class="panel-header">
                    <i class="fas fa-plus-circle"></i>
                    <h2>Tambah Fashion Item</h2>
                </div>

                <form id="fashionForm" class="fashion-form" action="{{ route('admin.dashboard.fashion-items.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label>Gambar Fashion <span class="required">*</span></label>
                        <div class="tag-group" id="imageSourceTags" style="margin-bottom: 12px;">
                            <span class="tag" data-value="upload">Image Upload</span>
                            <span class="tag" data-value="url">Image URL</span>
                        </div>
                        <input type="hidden" id="selectedImageSource" name="image_source" value="{{ old('image_source', 'upload') }}">

                        <div id="uploadSourceGroup" style="{{ old('image_source', 'upload') === 'url' ? 'display:none;' : '' }}">
                            <div class="upload-area" onclick="document.getElementById('imageInput').click()">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <p>Klik untuk upload gambar</p>
                                <span>JPG, PNG, WEBP (Max 2MB)</span>
                            </div>
                            <input type="file" id="imageInput" name="image_file" accept="image/*" style="display: none;">
                        </div>

                        <div id="urlSourceGroup" style="{{ old('image_source', 'upload') === 'url' ? 'display:block; margin-top:12px;' : 'display:none; margin-top:12px;' }}">
                            <input type="url" id="imageUrlInput" name="image_url" value="{{ old('image_url') }}" placeholder="https://example.com/image.jpg">
                        </div>

                        <div class="image-preview" id="imagePreview">
                            <img id="previewImg" src="" alt="Preview Image">
                            <button type="button" class="remove-img" onclick="removeImage()"><i class="fas fa-times"></i></button>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Nama Fashion <span class="required">*</span></label>
                            <input type="text" id="fashionName" name="title" value="{{ old('title') }}" placeholder="Contoh: Floral Summer Dress">
                        </div>
                        <div class="form-group">
                            <label>Body Type <span class="required">*</span></label>
                            <div class="tag-group" id="bodyTypeTags">
                                <span class="tag" data-value="hourglass">⌛ Hourglass</span>
                                <span class="tag" data-value="rectangle">◻️ Rectangle</span>
                                <span class="tag" data-value="spoon">🥄 Spoon</span>
                                <span class="tag" data-value="triangle">▼ Triangle</span>
                                <span class="tag" data-value="inverted">▲ Inverted</span>
                            </div>
                            <input type="hidden" id="selectedBodyType" name="body_type" value="{{ old('body_type') }}">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Style Preference <span class="required">*</span></label>
                            <div class="tag-group" id="styleTags">
                                <span class="tag" data-value="casual">👕 Casual</span>
                                <span class="tag" data-value="formal">👔 Formal</span>
                                <span class="tag" data-value="sporty">🏃 Sporty</span>
                                <span class="tag" data-value="classic">🕰️ Classic</span>
                                <span class="tag" data-value="bohemian">🌿 Bohemian</span>
                            </div>
                            <input type="hidden" id="selectedStyle" name="style_preference" value="{{ old('style_preference') }}">
                        </div>
                        <div class="form-group">
                            <label>Color Tone</label>
                            <div class="tag-group" id="colorTags">
                                <span class="tag" data-value="light">☀️ Light</span>
                                <span class="tag" data-value="bright">🌈 Bright</span>
                                <span class="tag" data-value="neutral">⚪ Neutral</span>
                                <span class="tag" data-value="dark">🌙 Dark</span>
                                <span class="tag" data-value="earth">🌍 Earth</span>
                            </div>
                            <input type="hidden" id="selectedColor" name="color_tone" value="{{ old('color_tone') }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Deskripsi <span class="required">*</span></label>
                        <textarea id="description" name="description" rows="3" placeholder="Deskripsi detail tentang fashion item ini...">{{ old('description') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label>Toko Penyedia <span class="required">*</span></label>
                        <div class="store-input-group">
                            <input type="text" id="storeName" placeholder="Nama Toko">
                            <input type="text" id="storeLink" placeholder="Link Toko (opsional)">
                            <button type="button" class="btn-add" onclick="addStore()">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        <div class="store-list" id="storeList"></div>
                        <input type="hidden" id="storesData" name="stores_payload" value="{{ old('stores_payload', '[]') }}">
                    </div>

                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save"></i> Simpan Fashion Item
                    </button>
                </form>
            </div>

            <div class="list-panel">
                <div class="panel-header">
                    <i class="fas fa-tshirt"></i>
                    <h2>Koleksi Fashion</h2>
                    <div class="search-wrapper">
                        <i class="fas fa-search"></i>
                        <input type="text" id="searchInput" placeholder="Cari fashion...">
                    </div>
                </div>

                <div class="fashion-grid" id="fashionGrid"></div>
            </div>
        </div>

        <form action="{{ route('admin.logout') }}" method="POST" class="logout-form">
            @csrf
            <button type="submit" class="btn-logout">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </button>
        </form>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
@endpush

@push('scripts')
<script id="dashboardInitialData" type="application/json">
{!! json_encode([
        'fashionItems' => ($fashionItemsPayload ?? collect())->values()->all(),
        'oldStoresPayload' => old('stores_payload', '[]'),
        'deleteEndpointTemplate' => route('admin.dashboard.fashion-items.destroy', ['id' => '__ID__']),
    ]) !!}
</script>
<script>
    const initialData = JSON.parse(document.getElementById('dashboardInitialData').textContent || '{}');
    const initialFashionItems = Array.isArray(initialData.fashionItems) ? initialData.fashionItems : [];
    const oldStoresPayload = initialData.oldStoresPayload || '[]';
    const deleteEndpointTemplate = String(initialData.deleteEndpointTemplate || '');

    let fashionItems = Array.isArray(initialFashionItems) ? initialFashionItems : [];
    let selectedBodyType = '';
    let selectedStyle = '';
    let selectedColor = '';
    let selectedImageSource = 'upload';
    let storesArray = [];

    const bodyLabelMap = {
        hourglass: '⌛ Hourglass',
        rectangle: '◻️ Rectangle',
        spoon: '🥄 Spoon',
        triangle: '▼ Triangle',
        inverted_triangle: '▲ Inverted Triangle',
        y_shape: '▲ Y',
        u: '⬭ U',
        inverted_u: '⇵ Inverted U',
        diamond: '◆ Diamond',
    };

    const styleLabelMap = {
        casual: '👕 Casual',
        formal: '👔 Formal',
        sporty: '🏃 Sporty',
        classic: '🕰️ Classic',
        bohemian: '🌿 Bohemian',
    };

    const colorLabelMap = {
        light: '☀️ Light',
        bright: '🌈 Bright',
        neutral: '⚪ Neutral',
        dark: '🌙 Dark',
        earth: '🌍 Earth',
    };

    function normalizeImageSource(value) {
        return value === 'url' ? 'url' : 'upload';
    }

    function setImagePreviewFromUrl(url) {
        const preview = document.getElementById('imagePreview');
        const previewImg = document.getElementById('previewImg');
        const cleanedUrl = String(url || '').trim();

        if (!cleanedUrl) {
            preview.style.display = 'none';
            previewImg.src = '';
            return;
        }

        previewImg.src = cleanedUrl;
        preview.style.display = 'flex';
    }

    function applyImageSource(source) {
        selectedImageSource = normalizeImageSource(source);

        const sourceInput = document.getElementById('selectedImageSource');
        const uploadGroup = document.getElementById('uploadSourceGroup');
        const urlGroup = document.getElementById('urlSourceGroup');
        const imageInput = document.getElementById('imageInput');
        const imageUrlInput = document.getElementById('imageUrlInput');

        sourceInput.value = selectedImageSource;
        activateTag('#imageSourceTags', selectedImageSource);

        uploadGroup.style.display = selectedImageSource === 'upload' ? '' : 'none';
        urlGroup.style.display = selectedImageSource === 'url' ? 'block' : 'none';

        if (selectedImageSource === 'upload') {
            setImagePreviewFromUrl('');
            return;
        }

        imageInput.value = '';
        setImagePreviewFromUrl(imageUrlInput.value);
    }

    function escapeHtml(value) {
        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function parseStoresPayload(rawValue) {
        let rawStores = [];

        if (Array.isArray(rawValue)) {
            rawStores = rawValue;
        } else if (typeof rawValue === 'string' && rawValue.trim() !== '') {
            try {
                const parsed = JSON.parse(rawValue);
                rawStores = Array.isArray(parsed) ? parsed : [];
            } catch (error) {
                rawStores = [];
            }
        }

        return rawStores
            .map((store) => ({
                name: String(store?.name ?? '').trim(),
                link: String(store?.link ?? '').trim(),
            }))
            .filter((store) => store.name !== '');
    }

    function updateStats() {
        document.getElementById('totalItemsCount').innerText = fashionItems.length;

        const uniqueStores = [
            ...new Set(
                fashionItems
                    .flatMap((item) => Array.isArray(item.stores) ? item.stores : [])
                    .map((store) => String(store?.name ?? '').trim().toLowerCase())
                    .filter((name) => name !== '')
            ),
        ];

        document.getElementById('totalStoresCount').innerText = uniqueStores.length;
    }

    function renderFashionGrid() {
        const grid = document.getElementById('fashionGrid');
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();

        const filtered = fashionItems.filter((item) => {
            const itemName = String(item.name ?? '').toLowerCase();
            const bodyLabel = String(bodyLabelMap[item.bodyType] || item.bodyTypeLabel || '').toLowerCase();
            const styleLabel = String(styleLabelMap[item.style] || item.styleLabel || '').toLowerCase();

            return itemName.includes(searchTerm) || bodyLabel.includes(searchTerm) || styleLabel.includes(searchTerm);
        });

        if (filtered.length === 0) {
            grid.innerHTML = '<div class="empty-state"><i class="fas fa-tshirt"></i><p>Belum ada fashion item</p><span>Silakan tambah item baru</span></div>';
            return;
        }

        grid.innerHTML = filtered.map((item) => {
            const description = String(item.description ?? '');
            const trimmedDescription = description.length > 80 ? `${description.substring(0, 80)}...` : description;
            const stores = Array.isArray(item.stores) ? item.stores : [];
            const storeNames = stores.map((store) => store.name).filter(Boolean).join(', ');
            const bodyLabel = bodyLabelMap[item.bodyType] || item.bodyTypeLabel || item.bodyType || '';
            const styleLabel = styleLabelMap[item.style] || item.styleLabel || item.style || '';
            const colorLabel = colorLabelMap[item.color] || item.colorLabel || '⚪ Neutral';

            return `
                <div class="fashion-card">
                    <div class="card-image">
                        <img src="${escapeHtml(item.image)}" alt="${escapeHtml(item.name)}" onerror="this.src='https://placehold.co/600x800/f5f0ed/1B1B1B?text=Image+Not+Found'">
                        <div class="card-actions">
                            <button class="action-btn edit" onclick="editItem(${Number(item.id)})"><i class="fas fa-edit"></i></button>
                            <button class="action-btn delete" onclick="deleteItem(${Number(item.id)})"><i class="fas fa-trash"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <h3>${escapeHtml(item.name)}</h3>
                        <div class="card-tags">
                            <span class="tag-badge body">${escapeHtml(bodyLabel)}</span>
                            <span class="tag-badge style">${escapeHtml(styleLabel)}</span>
                            <span class="tag-badge color">${escapeHtml(colorLabel)}</span>
                        </div>
                        <p class="card-desc">${escapeHtml(trimmedDescription)}</p>
                        <div class="card-stores"><i class="fas fa-store"></i> ${escapeHtml(storeNames || 'Belum ada toko')}</div>
                    </div>
                </div>
            `;
        }).join('');
    }

    function activateTag(groupSelector, value) {
        const tags = document.querySelectorAll(`${groupSelector} .tag`);
        tags.forEach((tag) => {
            tag.classList.toggle('active', tag.dataset.value === value);
        });
    }

    function initializeTagSelections() {
        const bodyTypeHidden = document.getElementById('selectedBodyType');
        const styleHidden = document.getElementById('selectedStyle');
        const colorHidden = document.getElementById('selectedColor');
        const imageSourceHidden = document.getElementById('selectedImageSource');

        selectedBodyType = String(bodyTypeHidden.value || '').trim();
        selectedStyle = String(styleHidden.value || '').trim();
        selectedColor = String(colorHidden.value || '').trim();
        selectedImageSource = normalizeImageSource(String(imageSourceHidden.value || 'upload').trim());

        const uiBodyType = selectedBodyType === 'inverted_triangle' ? 'inverted' : selectedBodyType;

        if (uiBodyType) {
            activateTag('#bodyTypeTags', uiBodyType);
        }

        if (selectedStyle) {
            activateTag('#styleTags', selectedStyle);
        }

        if (selectedColor) {
            activateTag('#colorTags', selectedColor);
        }

        applyImageSource(selectedImageSource);
    }

    document.querySelectorAll('#bodyTypeTags .tag').forEach((tag) => {
        tag.addEventListener('click', function () {
            document.querySelectorAll('#bodyTypeTags .tag').forEach((el) => el.classList.remove('active'));
            this.classList.add('active');
            selectedBodyType = this.dataset.value;
            document.getElementById('selectedBodyType').value = selectedBodyType;
        });
    });

    document.querySelectorAll('#styleTags .tag').forEach((tag) => {
        tag.addEventListener('click', function () {
            document.querySelectorAll('#styleTags .tag').forEach((el) => el.classList.remove('active'));
            this.classList.add('active');
            selectedStyle = this.dataset.value;
            document.getElementById('selectedStyle').value = selectedStyle;
        });
    });

    document.querySelectorAll('#colorTags .tag').forEach((tag) => {
        tag.addEventListener('click', function () {
            document.querySelectorAll('#colorTags .tag').forEach((el) => el.classList.remove('active'));
            this.classList.add('active');
            selectedColor = this.dataset.value;
            document.getElementById('selectedColor').value = selectedColor;
        });
    });

    document.querySelectorAll('#imageSourceTags .tag').forEach((tag) => {
        tag.addEventListener('click', function () {
            applyImageSource(this.dataset.value);
        });
    });

    document.getElementById('imageInput').addEventListener('change', function (event) {
        if (event.target.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('previewImg').src = e.target.result;
                document.getElementById('imagePreview').style.display = 'flex';
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    });

    document.getElementById('imageUrlInput').addEventListener('input', function () {
        if (selectedImageSource !== 'url') {
            return;
        }

        setImagePreviewFromUrl(this.value);
    });

    function removeImage() {
        setImagePreviewFromUrl('');

        if (selectedImageSource === 'upload') {
            document.getElementById('imageInput').value = '';
            return;
        }

        document.getElementById('imageUrlInput').value = '';
    }

    function addStore() {
        const name = document.getElementById('storeName').value.trim();
        const link = document.getElementById('storeLink').value.trim();

        if (!name) {
            alert('Nama toko wajib diisi!');
            return;
        }

        storesArray.push({ name, link });
        renderStoreList();
        document.getElementById('storeName').value = '';
        document.getElementById('storeLink').value = '';
    }

    function removeStore(index) {
        storesArray.splice(index, 1);
        renderStoreList();
    }

    function renderStoreList() {
        const container = document.getElementById('storeList');

        if (storesArray.length === 0) {
            container.innerHTML = '<p class="empty-stores">Belum ada toko, tambahkan toko penyedia</p>';
        } else {
            container.innerHTML = storesArray.map((store, index) => `
                <div class="store-item">
                    <span><i class="fas fa-store"></i> ${escapeHtml(store.name)}</span>
                    <button class="remove-store" onclick="removeStore(${index})"><i class="fas fa-times-circle"></i></button>
                </div>
            `).join('');
        }

        document.getElementById('storesData').value = JSON.stringify(storesArray);
    }

    document.getElementById('fashionForm').addEventListener('submit', function (event) {
        const name = document.getElementById('fashionName').value.trim();
        const description = document.getElementById('description').value.trim();
        const imageInput = document.getElementById('imageInput');
        const imageUrlInput = document.getElementById('imageUrlInput');
        const imageUrl = imageUrlInput.value.trim();

        if (!name) {
            alert('Nama fashion wajib diisi!');
            event.preventDefault();
            return;
        }

        if (!selectedBodyType) {
            alert('Pilih body type!');
            event.preventDefault();
            return;
        }

        if (!selectedStyle) {
            alert('Pilih style preference!');
            event.preventDefault();
            return;
        }

        if (!description) {
            alert('Deskripsi wajib diisi!');
            event.preventDefault();
            return;
        }

        if (selectedImageSource === 'upload' && !imageInput.files[0]) {
            alert('Gambar fashion wajib diupload!');
            event.preventDefault();
            return;
        }

        if (selectedImageSource === 'url') {
            if (!imageUrl) {
                alert('Link gambar wajib diisi!');
                event.preventDefault();
                return;
            }

            try {
                new URL(imageUrl);
            } catch (error) {
                alert('Format link gambar tidak valid.');
                event.preventDefault();
                return;
            }
        }

        if (storesArray.length === 0) {
            alert('Tambah minimal 1 toko!');
            event.preventDefault();
            return;
        }

        document.getElementById('storesData').value = JSON.stringify(storesArray);
    });

    function editItem(id) {
        const item = fashionItems.find((fashionItem) => Number(fashionItem.id) === Number(id));
        if (!item || !item.editUrl) {
            alert('Link edit item tidak tersedia.');
            return;
        }

        window.location.href = item.editUrl;
    }

    function deleteItem(id) {
        if (!confirm('Hapus item ini?')) {
            return;
        }

        const action = deleteEndpointTemplate.replace('__ID__', String(id));
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

        if (!csrfToken) {
            alert('CSRF token tidak ditemukan. Refresh halaman lalu coba lagi.');
            return;
        }

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = action;

        const tokenInput = document.createElement('input');
        tokenInput.type = 'hidden';
        tokenInput.name = '_token';
        tokenInput.value = csrfToken;

        form.appendChild(tokenInput);
        document.body.appendChild(form);
        form.submit();
    }

    document.getElementById('searchInput').addEventListener('input', renderFashionGrid);

    storesArray = parseStoresPayload(oldStoresPayload);
    initializeTagSelections();
    renderStoreList();
    updateStats();
    renderFashionGrid();

    window.addStore = addStore;
    window.removeStore = removeStore;
    window.removeImage = removeImage;
    window.editItem = editItem;
    window.deleteItem = deleteItem;
</script>
@endpush
