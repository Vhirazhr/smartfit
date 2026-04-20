<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin - SMARTfit</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@500;700;800&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Manrope', sans-serif;
            background: #0a0a0a;
            color: white;
            min-height: 100vh;
        }

        /* Navbar */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 40px;
            background: rgba(10, 10, 10, 0.95);
            border-bottom: 1px solid rgba(255,255,255,0.08);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }

        .logo {
            font-family: 'Bricolage Grotesque', sans-serif;
            font-size: 24px;
            font-weight: 800;
            background: linear-gradient(135deg, #fff 0%, #a855f7 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .nav-links {
            display: flex;
            gap: 30px;
            align-items: center;
        }

        .nav-links a {
            color: #a1a1aa;
            text-decoration: none;
            font-weight: 500;
            transition: 0.3s;
            font-size: 14px;
        }

        .nav-links a:hover {
            color: white;
        }

        .admin-badge {
            background: rgba(139, 92, 246, 0.2);
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            color: #a855f7;
            border: 1px solid rgba(139, 92, 246, 0.3);
        }

        /* Main Container */
        .dashboard-container {
            padding-top: 80px;
            min-height: 100vh;
            background: linear-gradient(135deg, #0a0a0a 0%, #0f0f1a 100%);
        }

        /* Header */
        .dashboard-header {
            padding: 30px 40px 20px 40px;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        .dashboard-header h1 {
            font-family: 'Bricolage Grotesque', sans-serif;
            font-size: 32px;
            font-weight: 700;
            background: linear-gradient(135deg, #fff, #c084fc);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .dashboard-header p {
            color: #a1a1aa;
            font-size: 14px;
            margin-top: 5px;
        }

        /* Content */
        .content-wrapper {
            padding: 30px 40px;
        }

        /* Form Section */
        .form-card {
            background: rgba(20, 20, 30, 0.6);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(168, 85, 247, 0.15);
            border-radius: 24px;
            padding: 30px;
            margin-bottom: 30px;
        }

        .form-title {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }

        .form-title i {
            font-size: 24px;
            color: #c084fc;
        }

        .form-title h2 {
            font-size: 20px;
            font-weight: 600;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 25px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-group.full-width {
            grid-column: span 2;
        }

        .form-group label {
            font-size: 13px;
            font-weight: 600;
            color: #d4d4d8;
        }

        .form-group label .required {
            color: #ef4444;
            margin-left: 4px;
        }

        input, select, textarea {
            padding: 12px 16px;
            background: rgba(15, 15, 25, 0.8);
            border: 1px solid rgba(168, 85, 247, 0.2);
            border-radius: 12px;
            font-family: 'Manrope', sans-serif;
            font-size: 14px;
            color: white;
            outline: none;
            transition: 0.3s;
        }

        input:focus, select:focus, textarea:focus {
            border-color: #a855f7;
            box-shadow: 0 0 0 3px rgba(168, 85, 247, 0.1);
        }

        textarea {
            resize: vertical;
            min-height: 80px;
        }

        /* Image Upload */
        .image-upload {
            border: 2px dashed rgba(168, 85, 247, 0.3);
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: 0.3s;
            background: rgba(15, 15, 25, 0.5);
        }

        .image-upload:hover {
            border-color: #a855f7;
            background: rgba(139, 92, 246, 0.05);
        }

        .image-upload i {
            font-size: 40px;
            color: #c084fc;
            margin-bottom: 10px;
        }

        .image-upload p {
            font-size: 12px;
            color: #a1a1aa;
        }

        .image-preview {
            margin-top: 15px;
            display: none;
        }

        .image-preview img {
            max-width: 100%;
            max-height: 150px;
            border-radius: 8px;
        }

        /* Category Tags */
        .category-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 8px;
        }

        .tag {
            padding: 8px 18px;
            background: rgba(15, 15, 25, 0.8);
            border: 1px solid rgba(168, 85, 247, 0.2);
            border-radius: 30px;
            font-size: 13px;
            cursor: pointer;
            transition: 0.3s;
        }

        .tag:hover, .tag.active {
            background: linear-gradient(135deg, #7c3aed, #a855f7);
            border-color: transparent;
        }

        /* Store Input Group */
        .store-input-group {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
        }

        .store-input-group input {
            flex: 1;
        }

        .add-store-btn {
            padding: 12px 20px;
            background: rgba(139, 92, 246, 0.2);
            border: 1px solid rgba(168, 85, 247, 0.3);
            border-radius: 10px;
            color: #c084fc;
            cursor: pointer;
            transition: 0.3s;
        }

        .add-store-btn:hover {
            background: rgba(139, 92, 246, 0.3);
        }

        .store-list {
            margin-top: 15px;
        }

        .store-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 12px;
            background: rgba(15, 15, 25, 0.5);
            border-radius: 10px;
            margin-bottom: 8px;
        }

        .store-item span {
            font-size: 13px;
        }

        .remove-store {
            color: #ef4444;
            cursor: pointer;
            background: none;
            border: none;
            font-size: 16px;
        }

        /* Submit Button */
        .submit-btn {
            margin-top: 25px;
            padding: 14px 30px;
            background: linear-gradient(135deg, #7c3aed, #a855f7);
            border: none;
            border-radius: 12px;
            color: white;
            font-weight: 700;
            font-size: 15px;
            cursor: pointer;
            transition: 0.3s;
            width: 100%;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(124, 58, 237, 0.3);
        }

        /* Items List */
        .items-list {
            background: rgba(20, 20, 30, 0.6);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(168, 85, 247, 0.15);
            border-radius: 24px;
            padding: 30px;
        }

        .items-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }

        .items-header h2 {
            font-size: 20px;
            font-weight: 600;
        }

        .search-box {
            padding: 10px 16px;
            width: 250px;
        }

        .fashion-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
        }

        .fashion-item {
            background: rgba(15, 15, 25, 0.8);
            border: 1px solid rgba(168, 85, 247, 0.15);
            border-radius: 16px;
            overflow: hidden;
            transition: 0.3s;
        }

        .fashion-item:hover {
            transform: translateY(-5px);
            border-color: rgba(168, 85, 247, 0.4);
        }

        .fashion-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            background: #1a1a2e;
        }

        .fashion-info {
            padding: 16px;
        }

        .fashion-info h3 {
            font-size: 16px;
            margin-bottom: 8px;
        }

        .fashion-categories {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-bottom: 10px;
        }

        .fashion-badge {
            font-size: 10px;
            padding: 4px 10px;
            background: rgba(139, 92, 246, 0.2);
            border-radius: 20px;
            color: #c084fc;
        }

        .fashion-desc {
            font-size: 12px;
            color: #a1a1aa;
            margin-bottom: 10px;
            line-height: 1.4;
        }

        .fashion-stores {
            font-size: 11px;
            color: #71717a;
        }

        .fashion-stores i {
            color: #c084fc;
            margin-right: 5px;
        }

        .item-actions {
            display: flex;
            gap: 10px;
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid rgba(255,255,255,0.08);
        }

        .edit-btn, .delete-btn {
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 12px;
            cursor: pointer;
            border: none;
            font-weight: 500;
        }

        .edit-btn {
            background: rgba(139, 92, 246, 0.2);
            color: #c084fc;
        }

        .delete-btn {
            background: rgba(239, 68, 68, 0.2);
            color: #fca5a5;
        }

        /* Logout */
        .logout-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            padding: 14px 24px;
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.2), rgba(220, 38, 38, 0.1));
            border: 1px solid rgba(239, 68, 68, 0.3);
            border-radius: 40px;
            color: #fca5a5;
            cursor: pointer;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
            z-index: 100;
        }

        @media (max-width: 900px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            .form-group.full-width {
                grid-column: span 1;
            }
            .content-wrapper {
                padding: 20px;
            }
            .navbar {
                padding: 16px 24px;
            }
        }
    </style>
</head>
<body>

<nav class="navbar">
    <div class="logo">SMARTFIT</div>
    <div class="nav-links">
        <a href="/smartfit/start"><i class="fas fa-globe"></i> Lihat Website</a>
        <span class="admin-badge"><i class="fas fa-shield-alt"></i> Admin Panel</span>
    </div>
</nav>

<div class="dashboard-container">
    <div class="dashboard-header">
        <h1><i class="fas fa-tshirt"></i> Fashion Management</h1>
    </div>

    <div class="content-wrapper">
        <!-- Form Tambah Fashion -->
        <div class="form-card">
            <div class="form-title">
                <i class="fas fa-plus-circle"></i>
                <h2>Tambah Fashion Item</h2>
            </div>

            <form id="fashionForm">
                <div class="form-grid">
                    <!-- Gambar Fashion -->
                    <div class="form-group full-width">
                        <label>Gambar Fashion <span class="required">*</span></label>
                        <div class="image-upload" onclick="document.getElementById('imageInput').click()">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <p>Klik untuk upload gambar fashion</p>
                        </div>
                        <input type="file" id="imageInput" accept="image/*" style="display: none;">
                        <div class="image-preview" id="imagePreview">
                            <img id="previewImg" src="">
                        </div>
                    </div>

                    <!-- Nama Fashion -->
                    <div class="form-group">
                        <label>Nama Fashion <span class="required">*</span></label>
                        <input type="text" id="fashionName" placeholder="Contoh: Floral Summer Dress" required>
                    </div>

                    <!-- Kategori Badan -->
                    <div class="form-group">
                        <label>Kategori Badan <span class="required">*</span></label>
                        <div class="category-tags" id="bodyTypeTags">
                            <div class="tag" data-value="apple">🍎 Apple (Apel)</div>
                            <div class="tag" data-value="pear">🍐 Pear (Pir)</div>
                            <div class="tag" data-value="hourglass">⏳ Hourglass (Jam Pasir)</div>
                            <div class="tag" data-value="rectangle">📏 Rectangle (Persegi)</div>
                            <div class="tag" data-value="inverted">🔻 Inverted Triangle</div>
                        </div>
                        <input type="hidden" id="selectedBodyType">
                    </div>

                    <!-- Style Preference -->
                    <div class="form-group">
                        <label>Style Preference (Kategori Fashion) <span class="required">*</span></label>
                        <div class="category-tags" id="styleTags">
                            <div class="tag" data-value="casual">👕 Casual</div>
                            <div class="tag" data-value="formal">👔 Formal</div>
                            <div class="tag" data-value="sporty">🏃 Sporty</div>
                            <div class="tag" data-value="vintage">🎞️ Vintage</div>
                            <div class="tag" data-value="streetwear">🛹 Streetwear</div>
                            <div class="tag" data-value="elegant">💎 Elegant</div>
                            <div class="tag" data-value="bohemian">🌿 Bohemian</div>
                            <div class="tag" data-value="minimalist">⚪ Minimalist</div>
                        </div>
                        <input type="hidden" id="selectedStyle">
                    </div>

                    <!-- Keterangan (Wajib Diisi) -->
                    <div class="form-group full-width">
                        <label>Keterangan <span class="required">*</span></label>
                        <textarea id="description" placeholder="Deskripsi detail tentang fashion item ini, bahan, ukuran, rekomendasi pemakaian..." required></textarea>
                    </div>

                    <!-- Toko Penyedia -->
                    <div class="form-group full-width">
                        <label>Keterangan Toko Penyedia <span class="required">*</span></label>
                        <div class="store-input-group">
                            <input type="text" id="storeName" placeholder="Nama Toko">
                            <input type="text" id="storeLink" placeholder="Link Toko (opsional)">
                            <button type="button" class="add-store-btn" onclick="addStore()">
                                <i class="fas fa-plus"></i> Tambah
                            </button>
                        </div>
                        <div class="store-list" id="storeList"></div>
                        <input type="hidden" id="storesData">
                    </div>
                </div>

                <button type="submit" class="submit-btn">
                    <i class="fas fa-save"></i> Simpan Fashion Item
                </button>
            </form>
        </div>

        <!-- Daftar Fashion Items -->
        <div class="items-list">
            <div class="items-header">
                <h2><i class="fas fa-tshirt"></i> Koleksi Fashion</h2>
                <input type="text" class="search-box" placeholder="Cari fashion..." id="searchInput">
            </div>
            <div class="fashion-grid" id="fashionGrid">
                <!-- Items akan diisi dengan JavaScript -->
            </div>
        </div>
    </div>
</div>

<button class="logout-btn" onclick="logout()">
    <i class="fas fa-sign-out-alt"></i> Logout
</button>

<script>
    // Data fashion items (simulasi database)
    let fashionItems = [
        {
            id: 1,
            image: "https://picsum.photos/id/20/300/200",
            name: "Floral Summer Dress",
            bodyType: "hourglass",
            bodyTypeLabel: "Hourglass (Jam Pasir)",
            style: "casual",
            styleLabel: "Casual",
            description: "Dress bunga dengan potongan yang pas di pinggang, sangat cocok untuk tubuh jam pasir. Bahan katun dingin nyaman dipakai sehari-hari.",
            stores: [
                { name: "Zalora", link: "https://zalora.co.id" },
                { name: "Shopee Fashion", link: "https://shopee.co.id" }
            ]
        },
        {
            id: 2,
            image: "https://picsum.photos/id/26/300/200",
            name: "Oversized Blazer",
            bodyType: "rectangle",
            bodyTypeLabel: "Rectangle (Persegi)",
            style: "formal",
            styleLabel: "Formal",
            description: "Blazer oversized dengan potongan tegas, memberikan ilusi bahu lebih bidang dan pinggang lebih ramping.",
            stores: [
                { name: "Blibli", link: "https://blibli.com" },
                { name: "Tokopedia", link: "https://tokopedia.com" }
            ]
        }
    ];

    let selectedBodyType = "";
    let selectedStyle = "";
    let storesArray = [];

    // Load data ke grid
    function renderFashionGrid() {
        const grid = document.getElementById('fashionGrid');
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        
        const filtered = fashionItems.filter(item => 
            item.name.toLowerCase().includes(searchTerm) ||
            item.styleLabel.toLowerCase().includes(searchTerm) ||
            item.bodyTypeLabel.toLowerCase().includes(searchTerm)
        );
        
        if (filtered.length === 0) {
            grid.innerHTML = '<div style="grid-column:1/-1; text-align:center; padding:40px; color:#a1a1aa;">Belum ada fashion item. Silakan tambah!</div>';
            return;
        }
        
        grid.innerHTML = filtered.map(item => `
            <div class="fashion-item">
                <img class="fashion-image" src="${item.image}" alt="${item.name}">
                <div class="fashion-info">
                    <h3>${item.name}</h3>
                    <div class="fashion-categories">
                        <span class="fashion-badge">${item.bodyTypeLabel}</span>
                        <span class="fashion-badge">${item.styleLabel}</span>
                    </div>
                    <div class="fashion-desc">${item.description.substring(0, 80)}${item.description.length > 80 ? '...' : ''}</div>
                    <div class="fashion-stores">
                        <i class="fas fa-store"></i> ${item.stores.map(s => s.name).join(', ')}
                    </div>
                    <div class="item-actions">
                        <button class="edit-btn" onclick="editItem(${item.id})"><i class="fas fa-edit"></i> Edit</button>
                        <button class="delete-btn" onclick="deleteItem(${item.id})"><i class="fas fa-trash"></i> Hapus</button>
                    </div>
                </div>
            </div>
        `).join('');
    }

    // Tag selection
    document.querySelectorAll('#bodyTypeTags .tag').forEach(tag => {
        tag.addEventListener('click', function() {
            document.querySelectorAll('#bodyTypeTags .tag').forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            selectedBodyType = this.dataset.value;
            document.getElementById('selectedBodyType').value = selectedBodyType;
        });
    });

    document.querySelectorAll('#styleTags .tag').forEach(tag => {
        tag.addEventListener('click', function() {
            document.querySelectorAll('#styleTags .tag').forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            selectedStyle = this.dataset.value;
            document.getElementById('selectedStyle').value = selectedStyle;
        });
    });

    // Image upload preview
    document.getElementById('imageInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                document.getElementById('previewImg').src = event.target.result;
                document.getElementById('imagePreview').style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    });

    // Store management
    function addStore() {
        const storeName = document.getElementById('storeName').value.trim();
        const storeLink = document.getElementById('storeLink').value.trim();
        
        if (!storeName) {
            alert('Nama toko wajib diisi!');
            return;
        }
        
        storesArray.push({ name: storeName, link: storeLink || '#' });
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
            container.innerHTML = '<p style="color:#52525b; font-size:12px;">Belum ada toko, tambahkan toko penyedia</p>';
        } else {
            container.innerHTML = storesArray.map((store, idx) => `
                <div class="store-item">
                    <span><i class="fas fa-store"></i> ${store.name} ${store.link !== '#' ? `<a href="${store.link}" target="_blank" style="color:#c084fc; font-size:11px;">(link)</a>` : ''}</span>
                    <button class="remove-store" onclick="removeStore(${idx})"><i class="fas fa-times-circle"></i></button>
                </div>
            `).join('');
        }
        document.getElementById('storesData').value = JSON.stringify(storesArray);
    }
    
    // Form submit
    document.getElementById('fashionForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const name = document.getElementById('fashionName').value.trim();
        const description = document.getElementById('description').value.trim();
        const imageFile = document.getElementById('imageInput').files[0];
        
        if (!name) {
            alert('Nama fashion wajib diisi!');
            return;
        }
        if (!selectedBodyType) {
            alert('Pilih kategori badan!');
            return;
        }
        if (!selectedStyle) {
            alert('Pilih style preference!');
            return;
        }
        if (!description) {
            alert('Keterangan wajib diisi!');
            return;
        }
        if (storesArray.length === 0) {
            alert('Minimal tambahkan 1 toko penyedia!');
            return;
        }
        
        // Get labels
        const bodyTypeLabel = document.querySelector('#bodyTypeTags .tag.active')?.innerText || selectedBodyType;
        const styleLabel = document.querySelector('#styleTags .tag.active')?.innerText || selectedStyle;
        
        const newItem = {
            id: Date.now(),
            image: imageFile ? URL.createObjectURL(imageFile) : "https://picsum.photos/id/30/300/200",
            name: name,
            bodyType: selectedBodyType,
            bodyTypeLabel: bodyTypeLabel,
            style: selectedStyle,
            styleLabel: styleLabel,
            description: description,
            stores: [...storesArray]
        };
        
        fashionItems.unshift(newItem);
        renderFashionGrid();
        
        // Reset form
        document.getElementById('fashionForm').reset();
        document.getElementById('imagePreview').style.display = 'none';
        document.getElementById('previewImg').src = '';
        document.querySelectorAll('#bodyTypeTags .tag').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('#styleTags .tag').forEach(t => t.classList.remove('active'));
        storesArray = [];
        renderStoreList();
        selectedBodyType = "";
        selectedStyle = "";
        
        alert('Fashion item berhasil ditambahkan!');
    });
    
    function deleteItem(id) {
        if (confirm('Yakin ingin menghapus fashion item ini?')) {
            fashionItems = fashionItems.filter(item => item.id !== id);
            renderFashionGrid();
        }
    }
    
    function editItem(id) {
        const item = fashionItems.find(i => i.id === id);
        if (item) {
            alert(`Edit: ${item.name}\nFitur edit sedang dalam pengembangan.`);
            // Di sini bisa diimplementasikan modal edit
        }
    }
    
    function logout() {
        if (confirm('Yakin ingin logout?')) {
            window.location.href = '/admin/login';
        }
    }
    
    document.getElementById('searchInput').addEventListener('input', renderFashionGrid);
    
    // Initial render
    renderFashionGrid();
    renderStoreList();
</script>

</body>
</html>