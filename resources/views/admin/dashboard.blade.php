@extends('layouts.app_nonavbar')

@section('title', 'SMARTfit - Admin Dashboard')

@section('content')
<div class="admin-dashboard">
    
    <div class="dashboard-container">
        
        <!-- Header -->
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

        <div style="display:flex; gap:10px; flex-wrap:wrap; margin: 0 0 18px;">
            <a href="{{ route('admin.fashion-categories.index') }}" class="btn-submit" style="text-decoration:none; width:auto; padding:10px 14px;">
                <i class="fas fa-tags"></i> Kelola Kategori
            </a>
            <a href="{{ route('admin.fashion-items.index') }}" class="btn-submit" style="text-decoration:none; width:auto; padding:10px 14px;">
                <i class="fas fa-tshirt"></i> Kelola Fashion Items
            </a>
        </div>
        
        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-tshirt"></i>
                </div>
                <div class="stat-info">
                    <h3 id="totalItemsCount">0</h3>
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
                    <h3 id="totalStoresCount">0</h3>
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
        
        <!-- Two Column Layout -->
        <div class="two-columns">
            
            <!-- LEFT: Form Tambah -->
            <div class="form-panel">
                <div class="panel-header">
                    <i class="fas fa-plus-circle"></i>
                    <h2>Tambah Fashion Item</h2>
                </div>
                
                <form id="fashionForm" class="fashion-form">
                    <!-- Image Upload -->
                    <div class="form-group">
                        <label>Gambar Fashion <span class="required">*</span></label>
                        <div class="upload-area" onclick="document.getElementById('imageInput').click()">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <p>Klik untuk upload gambar</p>
                            <span>JPG, PNG, WEBP (Max 2MB)</span>
                        </div>
                        <input type="file" id="imageInput" accept="image/*" style="display: none;">
                        <div class="image-preview" id="imagePreview">
                            <img id="previewImg" src="">
                            <button type="button" class="remove-img" onclick="removeImage()"><i class="fas fa-times"></i></button>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Nama Fashion <span class="required">*</span></label>
                            <input type="text" id="fashionName" placeholder="Contoh: Floral Summer Dress">
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
                            <input type="hidden" id="selectedBodyType">
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
                            <input type="hidden" id="selectedStyle">
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
                            <input type="hidden" id="selectedColor">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Deskripsi <span class="required">*</span></label>
                        <textarea id="description" rows="3" placeholder="Deskripsi detail tentang fashion item ini..."></textarea>
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
                        <input type="hidden" id="storesData">
                    </div>
                    
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save"></i> Simpan Fashion Item
                    </button>
                </form>
            </div>
            
            <!-- RIGHT: List Fashion -->
            <div class="list-panel">
                <div class="panel-header">
                    <i class="fas fa-tshirt"></i>
                    <h2>Koleksi Fashion</h2>
                    <div class="search-wrapper">
                        <i class="fas fa-search"></i>
                        <input type="text" id="searchInput" placeholder="Cari fashion...">
                    </div>
                </div>
                
                <div class="fashion-grid" id="fashionGrid">
                    <!-- JS akan mengisi -->
                </div>
            </div>
            
        </div>
        
        <!-- Logout Button -->
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
<script>
    // Data fashion items (simulasi database)
    let fashionItems = [
        {
            id: 1,
            image: "https://images.unsplash.com/photo-1539008835657-9e8e9680c956?w=400&h=280&fit=crop",
            name: "Floral Summer Dress",
            bodyType: "hourglass",
            bodyTypeLabel: "⌛ Hourglass",
            style: "casual",
            styleLabel: "👕 Casual",
            color: "light",
            colorLabel: "☀️ Light",
            description: "Dress bunga dengan potongan wrap yang pas di pinggang, sangat cocok untuk tubuh hourglass.",
            stores: [{ name: "Zalora", link: "#" }, { name: "Shopee", link: "#" }]
        },
        {
            id: 2,
            image: "https://images.unsplash.com/photo-1591047139829-d91aecb6caea?w=400&h=280&fit=crop",
            name: "Oversized Blazer",
            bodyType: "rectangle",
            bodyTypeLabel: "◻️ Rectangle",
            style: "formal",
            styleLabel: "👔 Formal",
            color: "neutral",
            colorLabel: "⚪ Neutral",
            description: "Blazer oversized dengan potongan tegas, memberikan ilusi bahu lebih bidang.",
            stores: [{ name: "Blibli", link: "#" }]
        }
    ];
    
    let selectedBodyType = "", selectedStyle = "", selectedColor = "";
    let storesArray = [];
    
    function updateStats() {
        document.getElementById('totalItemsCount').innerText = fashionItems.length;
        let uniqueStores = [...new Set(fashionItems.flatMap(i => i.stores.map(s => s.name)))];
        document.getElementById('totalStoresCount').innerText = uniqueStores.length;
    }
    
    function renderFashionGrid() {
        let grid = document.getElementById('fashionGrid');
        let searchTerm = document.getElementById('searchInput').value.toLowerCase();
        let filtered = fashionItems.filter(i => i.name.toLowerCase().includes(searchTerm) || i.bodyTypeLabel.toLowerCase().includes(searchTerm));
        
        if (filtered.length === 0) {
            grid.innerHTML = `<div class="empty-state"><i class="fas fa-tshirt"></i><p>Belum ada fashion item</p><span>Silakan tambah item baru</span></div>`;
            return;
        }
        
        grid.innerHTML = filtered.map(item => `
            <div class="fashion-card">
                <div class="card-image">
                    <img src="${item.image}" alt="${item.name}">
                    <div class="card-actions">
                        <button class="action-btn edit" onclick="editItem(${item.id})"><i class="fas fa-edit"></i></button>
                        <button class="action-btn delete" onclick="deleteItem(${item.id})"><i class="fas fa-trash"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <h3>${item.name}</h3>
                    <div class="card-tags">
                        <span class="tag-badge body">${item.bodyTypeLabel}</span>
                        <span class="tag-badge style">${item.styleLabel}</span>
                        <span class="tag-badge color">${item.colorLabel}</span>
                    </div>
                    <p class="card-desc">${item.description.substring(0, 80)}${item.description.length > 80 ? '...' : ''}</p>
                    <div class="card-stores"><i class="fas fa-store"></i> ${item.stores.map(s => s.name).join(', ')}</div>
                </div>
            </div>
        `).join('');
        updateStats();
    }
    
    // Tag selection
    document.querySelectorAll('#bodyTypeTags .tag').forEach(t => {
        t.addEventListener('click', function() {
            document.querySelectorAll('#bodyTypeTags .tag').forEach(x => x.classList.remove('active'));
            this.classList.add('active');
            selectedBodyType = this.dataset.value;
            document.getElementById('selectedBodyType').value = selectedBodyType;
        });
    });
    document.querySelectorAll('#styleTags .tag').forEach(t => {
        t.addEventListener('click', function() {
            document.querySelectorAll('#styleTags .tag').forEach(x => x.classList.remove('active'));
            this.classList.add('active');
            selectedStyle = this.dataset.value;
            document.getElementById('selectedStyle').value = selectedStyle;
        });
    });
    document.querySelectorAll('#colorTags .tag').forEach(t => {
        t.addEventListener('click', function() {
            document.querySelectorAll('#colorTags .tag').forEach(x => x.classList.remove('active'));
            this.classList.add('active');
            selectedColor = this.dataset.value;
            document.getElementById('selectedColor').value = selectedColor;
        });
    });
    
    // Image upload
    document.getElementById('imageInput').addEventListener('change', function(e) {
        if (e.target.files[0]) {
            let reader = new FileReader();
            reader.onload = function(ev) {
                document.getElementById('previewImg').src = ev.target.result;
                document.getElementById('imagePreview').style.display = 'flex';
            };
            reader.readAsDataURL(e.target.files[0]);
        }
    });
    function removeImage() {
        document.getElementById('imagePreview').style.display = 'none';
        document.getElementById('previewImg').src = '';
        document.getElementById('imageInput').value = '';
    }
    
    // Store management
    function addStore() {
        let name = document.getElementById('storeName').value.trim();
        let link = document.getElementById('storeLink').value.trim();
        if (!name) { alert('Nama toko wajib diisi!'); return; }
        storesArray.push({ name: name, link: link || '#' });
        renderStoreList();
        document.getElementById('storeName').value = '';
        document.getElementById('storeLink').value = '';
    }
    function removeStore(index) { storesArray.splice(index, 1); renderStoreList(); }
    function renderStoreList() {
        let container = document.getElementById('storeList');
        if (storesArray.length === 0) {
            container.innerHTML = '<p class="empty-stores">Belum ada toko, tambahkan toko penyedia</p>';
        } else {
            container.innerHTML = storesArray.map((s, i) => `
                <div class="store-item"><span><i class="fas fa-store"></i> ${s.name}</span><button class="remove-store" onclick="removeStore(${i})"><i class="fas fa-times-circle"></i></button></div>
            `).join('');
        }
        document.getElementById('storesData').value = JSON.stringify(storesArray);
    }
    
    // Submit form
    document.getElementById('fashionForm').addEventListener('submit', function(e) {
        e.preventDefault();
        let name = document.getElementById('fashionName').value.trim();
        let desc = document.getElementById('description').value.trim();
        if (!name) { alert('Nama fashion wajib diisi!'); return; }
        if (!selectedBodyType) { alert('Pilih body type!'); return; }
        if (!selectedStyle) { alert('Pilih style preference!'); return; }
        if (!desc) { alert('Deskripsi wajib diisi!'); return; }
        if (storesArray.length === 0) { alert('Tambah minimal 1 toko!'); return; }
        
        let bodyLabel = document.querySelector('#bodyTypeTags .tag.active')?.innerText || selectedBodyType;
        let styleLabel = document.querySelector('#styleTags .tag.active')?.innerText || selectedStyle;
        let colorLabel = document.querySelector('#colorTags .tag.active')?.innerText || selectedColor || '⚪ Neutral';
        let imgFile = document.getElementById('imageInput').files[0];
        
        let newItem = {
            id: Date.now(),
            image: imgFile ? URL.createObjectURL(imgFile) : "https://images.unsplash.com/photo-1539008835657-9e8e9680c956?w=400&h=280&fit=crop",
            name: name,
            bodyType: selectedBodyType,
            bodyTypeLabel: bodyLabel,
            style: selectedStyle,
            styleLabel: styleLabel,
            color: selectedColor || 'neutral',
            colorLabel: colorLabel,
            description: desc,
            stores: [...storesArray]
        };
        fashionItems.unshift(newItem);
        renderFashionGrid();
        
        // Reset form
        document.getElementById('fashionName').value = '';
        document.getElementById('description').value = '';
        document.querySelectorAll('.tag').forEach(t => t.classList.remove('active'));
        removeImage();
        storesArray = [];
        renderStoreList();
        selectedBodyType = selectedStyle = selectedColor = '';
        alert('Fashion item berhasil ditambahkan!');
    });
    
    function deleteItem(id) { if (confirm('Hapus item ini?')) { fashionItems = fashionItems.filter(i => i.id !== id); renderFashionGrid(); } }
    function editItem(id) { alert('Fitur edit sedang dalam pengembangan.'); }
    
    document.getElementById('searchInput').addEventListener('input', renderFashionGrid);
    renderFashionGrid();
    renderStoreList();
</script>
@endpush
