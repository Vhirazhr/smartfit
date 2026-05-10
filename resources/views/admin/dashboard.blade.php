@extends('layouts.app_nonavbar')

@section('title', 'SMARTfit - Admin Dashboard')

@section('content')
<div class="admin-wrapper">
    {{-- Overlay Mobile --}}
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

    {{-- SIDEBAR --}}
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="sidebar-brand">
            <h2>SMARTfit</h2>
            <span>Admin Panel</span>
        </div>

        <nav class="sidebar-nav">
            <div class="sidebar-section">
                <p class="sidebar-section-title">Menu Utama</p>
                <ul class="sidebar-menu">
                    <li>
                        <a href="#" class="active" data-page="dashboard" onclick="switchPage('dashboard', this)">
                            <i class="fas fa-chart-pie"></i>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="#" data-page="add-fashion" onclick="switchPage('add-fashion', this)">
                            <i class="fas fa-plus-circle"></i>
                            Tambah Fashion
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.smartfit-analytics.index') }}">
                            <i class="fas fa-chart-line"></i>
                            SmartFIT Analytics
                        </a>
                    </li>
                </ul>
            </div>

            <div class="sidebar-section">
                <p class="sidebar-section-title">Manajemen Data</p>
                <ul class="sidebar-menu">
                    <li>
                        <a href="{{ route('admin.fashion-categories.index') }}">
                            <i class="fas fa-tags"></i>
                            Kategori
                            <span class="badge">9</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.fashion-items.index') }}">
                            <i class="fas fa-tshirt"></i>
                            Semua Fashion
                            <span class="badge" id="sidebarItemCount">{{ $stats['total_items'] ?? 0 }}</span>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <div class="sidebar-footer">
            <div class="sidebar-user">
                <div class="sidebar-user-avatar">
                    <i class="fas fa-user-shield"></i>
                </div>
                <div class="sidebar-user-info">
                    <span class="name">Admin SMARTfit</span>
                    <span class="role">Administrator</span>
                </div>
            </div>
        </div>
    </aside>

    {{-- MAIN CONTENT --}}
    <main class="admin-main">
        {{-- Top Bar --}}
        <div class="top-bar">
            <div class="top-bar-left">
                <button class="mobile-menu-toggle" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="top-bar-text">
                    <h1 id="pageTitle">Dashboard</h1>
                    <p id="pageSubtitle">Ringkasan & analitik fashion</p>
                </div>
            </div>
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-logout">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>

        {{-- Content Area --}}
        <div class="content-area">
            {{-- Alert Sukses --}}
            @if(session('success'))
                <div class="alert-box alert-success">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            {{-- Alert Error --}}
            @if($errors->any())
                <div class="alert-box alert-error">
                    <i class="fas fa-exclamation-triangle"></i>
                    <div>
                        <strong>Periksa kembali:</strong>
                        <ul style="margin: 6px 0 0 16px; padding: 0;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            {{-- ========== HALAMAN DASHBOARD ========== --}}
            <div class="page-section active" id="page-dashboard">
                {{-- 4 Card Analitik --}}
                <div class="analytics-grid">
                    <div class="analytics-card">
                        <div class="analytics-card-header">
                            <div class="analytics-card-icon primary">
                                <i class="fas fa-tshirt"></i>
                            </div>
                        </div>
                        <div class="analytics-card-value" id="totalItemsCount">{{ $stats['total_items'] ?? 0 }}</div>
                        <div class="analytics-card-label">Total Fashion</div>
                        <div class="analytics-card-change positive">
                            <i class="fas fa-check-circle"></i> Items tersimpan
                        </div>
                    </div>

                    <div class="analytics-card">
                        <div class="analytics-card-header">
                            <div class="analytics-card-icon success">
                                <i class="fas fa-store"></i>
                            </div>
                        </div>
                        <div class="analytics-card-value" id="totalStoresCount">{{ $stats['total_stores'] ?? 0 }}</div>
                        <div class="analytics-card-label">Total Toko</div>
                        <div class="analytics-card-change positive">
                            <i class="fas fa-shop"></i> Toko terdaftar
                        </div>
                    </div>

                    <div class="analytics-card">
                        <div class="analytics-card-header">
                            <div class="analytics-card-icon warning">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                        <div class="analytics-card-value">{{ number_format($stats['smartfit_usage'] ?? 0) }}</div>
                        <div class="analytics-card-label">SmartFIT Usage</div>
                        <div class="analytics-card-change positive">
                            <i class="fas fa-calendar-day"></i> {{ number_format($stats['smartfit_usage_today'] ?? 0) }} hari ini
                        </div>
                    </div>

                    <div class="analytics-card">
                        <div class="analytics-card-header">
                            <div class="analytics-card-icon info">
                                <i class="fas fa-bullseye"></i>
                            </div>
                        </div>
                        <div class="analytics-card-value">81.9%</div>
                        <div class="analytics-card-label">Akurasi</div>
                        <div class="analytics-card-change positive">
                            <i class="fas fa-arrow-up"></i> +2.1% peningkatan
                        </div>
                    </div>
                </div>

                {{-- Dua Kolom: Daftar Fashion + Pengguna --}}
                <div class="panel-section">
                    {{-- Koleksi Fashion --}}
                    <div class="info-panel">
                        <div class="info-panel-header">
                            <h3>
                                <i class="fas fa-tshirt" style="color: #C5B09F;"></i> 
                                Koleksi Fashion
                            </h3>
                            <div class="search-wrapper">
                                <i class="fas fa-search"></i>
                                <input type="text" id="searchInput" placeholder="Cari fashion...">
                            </div>
                        </div>
                        <div class="info-panel-body">
                            <div class="fashion-grid" id="fashionGrid"></div>
                        </div>
                    </div>

                    {{-- SmartFIT Usage Terbaru --}}
                    <div class="info-panel">
                        <div class="info-panel-header">
                            <h3>
                                <i class="fas fa-chart-line" style="color: #C5B09F;"></i>
                                SmartFIT Terbaru
                            </h3>
                        </div>
                        <div class="info-panel-body" style="padding: 0;">
                            <table class="users-table">
                                <thead>
                                    <tr>
                                        <th>Flow</th>
                                        <th>Body Type</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentSmartfitUsage as $usageLog)
                                        <tr>
                                            <td>
                                                <div class="user-cell">
                                                    <div class="user-avatar-sm">{{ strtoupper(substr($usageLog->flow_type, 0, 1)) }}</div>
                                                    <div>
                                                        <div class="user-name">{{ $usageLog->flow_label }}</div>
                                                        <div class="user-email">{{ $usageLog->country_name ?: ($usageLog->country_code ?: 'Unknown country') }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="status-badge active">
                                                    {{ $smartfitLabels[$usageLog->morphotype] ?? $usageLog->body_type ?? '-' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="category-empty">Belum ada penggunaan SmartFIT.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ========== HALAMAN TAMBAH FASHION ========== --}}
            <div class="page-section" id="page-add-fashion">
                <div class="form-panel">
                    <div class="panel-header">
                        <i class="fas fa-plus-circle"></i>
                        <h2>Tambah Fashion Item Baru</h2>
                    </div>

                    <form id="fashionForm" class="fashion-form" action="{{ route('admin.dashboard.fashion-items.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label>Gambar Fashion <span class="required">*</span></label>
                            <div class="tag-group" id="imageSourceTags" style="margin-bottom: 12px;">
                                <span class="tag active" data-value="upload">Upload Gambar</span>
                                <span class="tag" data-value="url">URL Gambar</span>
                            </div>
                            <input type="hidden" id="selectedImageSource" name="image_source" value="{{ old('image_source', 'upload') }}">

                            <div id="uploadSourceGroup">
                                <div class="upload-area" onclick="document.getElementById('imageInput').click()">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <p>Klik untuk upload gambar</p>
                                    <span>JPG, PNG, WEBP (Max 2MB)</span>
                                </div>
                                <input type="file" id="imageInput" name="image_file" accept="image/*" style="display: none;">
                            </div>

                            <div id="urlSourceGroup" style="display: none; margin-top: 12px;">
                                <input type="url" id="imageUrlInput" name="image_url" value="{{ old('image_url') }}" placeholder="https://example.com/image.jpg">
                            </div>

                            <div class="image-preview" id="imagePreview">
                                <img id="previewImg" src="" alt="Preview">
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
                                <div class="option-grid" id="bodyTypeTags">
    <div class="option-card" data-value="hourglass">
        <div class="icon">⌛</div>
        Hourglass
    </div>
    <div class="option-card" data-value="rectangle">
        <div class="icon">◻️</div>
        Rectangle
    </div>
    <div class="option-card" data-value="spoon">
        <div class="icon">🥄</div>
        Spoon
    </div>
    <div class="option-card" data-value="triangle">
        <div class="icon">▼</div>
        Triangle
    </div>
    <div class="option-card" data-value="inverted">
        <div class="icon">▲</div>
        Inverted
    </div>
</div>
                                <input type="hidden" id="selectedBodyType" name="body_type" value="{{ old('body_type') }}">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>Style <span class="required">*</span></label>
                                <input type="hidden" id="selectedColor" name="color_tone" value="">
                                <div class="option-grid" id="styleTags">
    <div class="option-card" data-value="casual">
        <div class="icon">👕</div>
        Casual
    </div>
    <div class="option-card" data-value="formal">
        <div class="icon">👔</div>
        Formal
    </div>
    <div class="option-card" data-value="sporty">
        <div class="icon">🏃</div>
        Sporty
    </div>
    <div class="option-card" data-value="classic">
        <div class="icon">🕰️</div>
        Classic
    </div>
    <div class="option-card" data-value="bohemian">
        <div class="icon">🌿</div>
        Bohemian
    </div>
</div>
                                <input type="hidden" id="selectedStyle" name="style_preference" value="{{ old('style_preference') }}">
                            </div>

                        <div class="form-group">
                            <label>Deskripsi <span class="required">*</span></label>
                            <textarea id="description" name="description" rows="3" placeholder="Deskripsi detail fashion item...">{{ old('description') }}</textarea>
                        </div>
                    </div>
                        <div class="form-group">
                            <label>Toko Penyedia <span class="required">*</span></label>
                            <div class="store-input-group">
                                <input type="text" id="storeName" placeholder="Nama Toko">
                                <input type="text" id="storeLink" placeholder="Link (opsional)">
                                <button type="button" class="btn-add" onclick="addStore()">
                                    <i class="fas fa-plus"></i> Tambah
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
            </div>
        </div>
    </main>
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
], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}
</script>
<script>
    const initialData = JSON.parse(document.getElementById('dashboardInitialData').textContent || '{}');
    const initialFashionItems = Array.isArray(initialData.fashionItems) ? initialData.fashionItems : [];
    const oldStoresPayload = initialData.oldStoresPayload || '[]';
    const deleteEndpointTemplate = String(initialData.deleteEndpointTemplate || '');

    let fashionItems = [...initialFashionItems];
    let selectedBodyType = '';
    let selectedStyle = '';
    let selectedColor = '';
    let selectedImageSource = 'upload';
    let storesArray = [];

    const bodyLabelMap = {
        hourglass: '⌛ Hourglass', rectangle: '◻️ Rectangle', spoon: '🥄 Spoon',
        triangle: '▼ Triangle', inverted: '▲ Inverted', inverted_triangle: '▲ Inverted Triangle',
        y_shape: '▲ Y', u: '⬭ U', inverted_u: '⇵ Inverted U', diamond: '◆ Diamond',
    };
    const styleLabelMap = {
        casual: '👕 Casual', formal: '👔 Formal', sporty: '🏃 Sporty',
        classic: '🕰️ Classic', bohemian: '🌿 Bohemian',
    };
    const colorLabelMap = {
        light: '☀️ Light', bright: '🌈 Bright', neutral: '⚪ Neutral',
        dark: '🌙 Dark', earth: '🌍 Earth',
    };

    const pageTitles = {
        'dashboard': { title: 'Dashboard', subtitle: 'Ringkasan & analitik fashion' },
        'add-fashion': { title: 'Tambah Fashion', subtitle: 'Form tambah item baru' },
    };

    // ===== PAGE SWITCHING =====
    function switchPage(pageName, el) {
        document.querySelectorAll('.sidebar-menu a').forEach(a => a.classList.remove('active'));
        if (el) el.classList.add('active');
        document.querySelectorAll('.page-section').forEach(s => s.classList.remove('active'));
        const page = document.getElementById('page-' + pageName);
        if (page) page.classList.add('active');
        const info = pageTitles[pageName] || { title: pageName, subtitle: '' };
        document.getElementById('pageTitle').textContent = info.title;
        document.getElementById('pageSubtitle').textContent = info.subtitle;
        closeSidebar();
    }

    function toggleSidebar() {
        document.getElementById('adminSidebar').classList.toggle('open');
        document.getElementById('sidebarOverlay').classList.toggle('active');
    }

    function closeSidebar() {
        document.getElementById('adminSidebar').classList.remove('open');
        document.getElementById('sidebarOverlay').classList.remove('active');
    }

    // ===== HELPERS =====
    function escapeHtml(v) {
        return String(v ?? '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#039;');
    }

    function normalizeSource(v) { return v === 'url' ? 'url' : 'upload'; }

    function setPreview(url) {
        const p = document.getElementById('imagePreview');
        const img = document.getElementById('previewImg');
        const u = String(url || '').trim();
        if (!u) { p.style.display = 'none'; img.src = ''; return; }
        img.src = u;
        p.style.display = 'flex';
    }

    function applyImageSource(source) {
        selectedImageSource = normalizeSource(source);
        document.getElementById('selectedImageSource').value = selectedImageSource;
        activateTag('#imageSourceTags', selectedImageSource);
        document.getElementById('uploadSourceGroup').style.display = selectedImageSource === 'upload' ? '' : 'none';
        document.getElementById('urlSourceGroup').style.display = selectedImageSource === 'url' ? 'block' : 'none';
        if (selectedImageSource === 'upload') { setPreview(''); return; }
        document.getElementById('imageInput').value = '';
        setPreview(document.getElementById('imageUrlInput').value);
    }

    function activateTag(selector, value) {
        document.querySelectorAll(selector + ' .tag, ' + selector + ' .option-card').forEach(t => t.classList.toggle('active', t.dataset.value === value));
    }

    function parseStores(raw) {
        let arr = [];
        if (Array.isArray(raw)) arr = raw;
        else if (typeof raw === 'string' && raw.trim()) {
            try { const p = JSON.parse(raw); arr = Array.isArray(p) ? p : []; } catch(e) { arr = []; }
        }
        return arr.map(s => ({ name: String(s?.name ?? '').trim(), link: String(s?.link ?? '').trim() })).filter(s => s.name);
    }

    // ===== STATS & GRID =====
    function updateStats() {
        const el = document.getElementById('totalItemsCount');
        if (el) el.textContent = fashionItems.length;
        const sideEl = document.getElementById('sidebarItemCount');
        if (sideEl) sideEl.textContent = fashionItems.length;
        const uniqueStores = [...new Set(
            fashionItems.flatMap(item => Array.isArray(item.stores) ? item.stores : [])
            .map(s => String(s?.name ?? '').trim().toLowerCase()).filter(n => n)
        )];
        const storeEl = document.getElementById('totalStoresCount');
        if (storeEl) storeEl.textContent = uniqueStores.length;
    }

    function renderFashionGrid() {
        const grid = document.getElementById('fashionGrid');
        if (!grid) return;
        const term = (document.getElementById('searchInput')?.value || '').toLowerCase();
        const filtered = fashionItems.filter(item => {
            const name = String(item.name ?? '').toLowerCase();
            const body = String(bodyLabelMap[item.bodyType] || item.bodyTypeLabel || '').toLowerCase();
            const style = String(styleLabelMap[item.style] || item.styleLabel || '').toLowerCase();
            return name.includes(term) || body.includes(term) || style.includes(term);
        });

        if (!filtered.length) {
            grid.innerHTML = '<div class="empty-state"><i class="fas fa-tshirt"></i><p>Belum ada fashion item</p><span>Silakan tambah item baru</span></div>';
            return;
        }

        grid.innerHTML = filtered.map(item => {
            const desc = String(item.description ?? '');
            const trimmed = desc.length > 80 ? desc.substring(0, 80) + '...' : desc;
            const stores = Array.isArray(item.stores) ? item.stores : [];
            const storeNames = stores.map(s => s.name).filter(Boolean).join(', ');
            return `
                <div class="fashion-card">
                    <div class="card-image">
                        <img src="${escapeHtml(item.image)}" alt="${escapeHtml(item.name)}" onerror="this.src='https://placehold.co/600x800/f5f0ed/1B1B1B?text=No+Image'">
                        <div class="card-actions">
                            <button class="action-btn edit" onclick="editItem(${Number(item.id)})"><i class="fas fa-edit"></i></button>
                            <button class="action-btn delete" onclick="deleteItem(${Number(item.id)})"><i class="fas fa-trash"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <h3>${escapeHtml(item.name)}</h3>
                        <div class="card-tags">
                            <span class="tag-badge body">${escapeHtml(bodyLabelMap[item.bodyType] || item.bodyTypeLabel || item.bodyType || '')}</span>
                            <span class="tag-badge style">${escapeHtml(styleLabelMap[item.style] || item.styleLabel || item.style || '')}</span>
                            <span class="tag-badge color">${escapeHtml(colorLabelMap[item.color] || item.colorLabel || 'Neutral')}</span>
                        </div>
                        <p class="card-desc">${escapeHtml(trimmed)}</p>
                        <div class="card-stores"><i class="fas fa-store"></i> ${escapeHtml(storeNames || 'Belum ada toko')}</div>
                    </div>
                </div>
            `;
        }).join('');
    }

    // ===== STORE =====
    function addStore() {
        const name = document.getElementById('storeName').value.trim();
        const link = document.getElementById('storeLink').value.trim();
        if (!name) { alert('Nama toko wajib diisi!'); return; }
        storesArray.push({ name, link });
        renderStoreList();
        document.getElementById('storeName').value = '';
        document.getElementById('storeLink').value = '';
    }

    function removeStore(index) { storesArray.splice(index, 1); renderStoreList(); }

    function renderStoreList() {
        const container = document.getElementById('storeList');
        if (!container) return;
        container.innerHTML = storesArray.length === 0
            ? '<p class="empty-stores">Belum ada toko, tambahkan toko penyedia</p>'
            : storesArray.map((s, i) => `<div class="store-item"><span><i class="fas fa-store"></i> ${escapeHtml(s.name)}</span><button class="remove-store" onclick="removeStore(${i})"><i class="fas fa-times-circle"></i></button></div>`).join('');
        document.getElementById('storesData').value = JSON.stringify(storesArray);
    }

    function removeImage() {
        setPreview('');
        if (selectedImageSource === 'upload') { document.getElementById('imageInput').value = ''; return; }
        document.getElementById('imageUrlInput').value = '';
    }

    // ===== ITEM ACTIONS =====
    function editItem(id) {
        const item = fashionItems.find(fi => Number(fi.id) === Number(id));
        if (!item?.editUrl) { alert('Link edit tidak tersedia.'); return; }
        window.location.href = item.editUrl;
    }

    function deleteItem(id) {
        if (!confirm('Hapus item ini?')) return;
        const action = deleteEndpointTemplate.replace('__ID__', String(id));
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        if (!csrfToken) { alert('CSRF token tidak ditemukan.'); return; }
        const form = document.createElement('form');
        form.method = 'POST'; form.action = action;
        const input = document.createElement('input');
        input.type = 'hidden'; input.name = '_token'; input.value = csrfToken;
        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
    }

    // ===== INITIALIZATION =====
    function initTags() {
        const bType = document.getElementById('selectedBodyType');
        const style = document.getElementById('selectedStyle');
        const color = document.getElementById('selectedColor');
        const imgSrc = document.getElementById('selectedImageSource');

        selectedBodyType = String(bType?.value || '').trim();
        selectedStyle = String(style?.value || '').trim();
        selectedColor = String(color?.value || '').trim();
        selectedImageSource = normalizeSource(String(imgSrc?.value || 'upload').trim());

        const uiBT = selectedBodyType === 'inverted_triangle' ? 'inverted' : selectedBodyType;
        if (uiBT) activateTag('#bodyTypeTags', uiBT);
        if (selectedStyle) activateTag('#styleTags', selectedStyle);
        if (selectedColor) activateTag('#colorTags', selectedColor);
        applyImageSource(selectedImageSource);
    }

    // Event Listeners
    document.querySelectorAll('#bodyTypeTags .option-card').forEach(tag => {
        tag.addEventListener('click', function() {
            activateTag('#bodyTypeTags', this.dataset.value);
            selectedBodyType = this.dataset.value;
            document.getElementById('selectedBodyType').value = selectedBodyType;
        });
    });

    document.querySelectorAll('#styleTags .option-card').forEach(tag => {
        tag.addEventListener('click', function() {
            activateTag('#styleTags', this.dataset.value);
            selectedStyle = this.dataset.value;
            document.getElementById('selectedStyle').value = selectedStyle;
        });
    });

    document.querySelectorAll('#colorTags .tag').forEach(tag => {
        tag.addEventListener('click', function() {
            activateTag('#colorTags', this.dataset.value);
            selectedColor = this.dataset.value;
            document.getElementById('selectedColor').value = selectedColor;
        });
    });

    document.querySelectorAll('#imageSourceTags .tag').forEach(tag => {
        tag.addEventListener('click', function() { applyImageSource(this.dataset.value); });
    });

    document.getElementById('imageInput')?.addEventListener('change', function(e) {
        if (e.target.files[0]) {
            const reader = new FileReader();
            reader.onload = function(ev) {
                document.getElementById('previewImg').src = ev.target.result;
                document.getElementById('imagePreview').style.display = 'flex';
            };
            reader.readAsDataURL(e.target.files[0]);
        }
    });

    document.getElementById('imageUrlInput')?.addEventListener('input', function() {
        if (selectedImageSource === 'url') setPreview(this.value);
    });

    document.getElementById('fashionForm')?.addEventListener('submit', function(e) {
        const name = document.getElementById('fashionName').value.trim();
        const desc = document.getElementById('description').value.trim();
        const imgInput = document.getElementById('imageInput');
        const urlInput = document.getElementById('imageUrlInput');
        const url = urlInput.value.trim();

        if (!name) { alert('Nama fashion wajib diisi!'); e.preventDefault(); return; }
        if (!selectedBodyType) { alert('Pilih body type!'); e.preventDefault(); return; }
        if (!selectedStyle) { alert('Pilih style!'); e.preventDefault(); return; }
        if (!desc) { alert('Deskripsi wajib diisi!'); e.preventDefault(); return; }
        if (selectedImageSource === 'upload' && !imgInput.files[0]) { alert('Upload gambar!'); e.preventDefault(); return; }
        if (selectedImageSource === 'url') {
            if (!url) { alert('Isi URL gambar!'); e.preventDefault(); return; }
            try { new URL(url); } catch(err) { alert('URL tidak valid!'); e.preventDefault(); return; }
        }
        if (storesArray.length === 0) { alert('Tambah minimal 1 toko!'); e.preventDefault(); return; }
        document.getElementById('storesData').value = JSON.stringify(storesArray);
    });

    document.getElementById('searchInput')?.addEventListener('input', renderFashionGrid);

    // Init
    storesArray = parseStores(oldStoresPayload);
    initTags();
    renderStoreList();
    updateStats();
    renderFashionGrid();

    // Global
    window.addStore = addStore;
    window.removeStore = removeStore;
    window.removeImage = removeImage;
    window.editItem = editItem;
    window.deleteItem = deleteItem;
    window.switchPage = switchPage;
    window.toggleSidebar = toggleSidebar;
    window.closeSidebar = closeSidebar;
</script>
@endpush
