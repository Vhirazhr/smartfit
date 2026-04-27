## Plan Implementasi Final: Dynamic Fashion Gallery + NNM Body Morphotype (9 Kategori)

Status: Final (siap build)

Baseline referensi: Pandarum et al., *A normative method for the classification and assessment of women's 3-D scanned morphotypes* (IJCST, DOI: 10.1108/IJCST-06-2020-0089)

### 1) Tujuan

Membangun modul galeri fashion berbasis database agar admin dapat mengelola kategori dan item fashion secara dinamis (CRUD + filter + pencarian), sekaligus menyelaraskan perhitungan body morphotype dengan metode NNM dari jurnal referensi (9 kategori) secara konsisten di API dan web flow.

### 2) Scope

**In scope**
- CRUD admin untuk `fashion_categories` dan `fashion_items`
- Filter/pencarian admin (keyword, kategori, body type, status)
- Input gambar mode `upload` atau `url`
- Field `purchase_link` untuk tautan pembelian item
- Integrasi halaman publik `/gallery` ke data database (bukan hardcoded JS)
- Refactor klasifikasi morphotype ke 9 kategori NNM
- Sinkronisasi label/rekomendasi/mapping manual body type ke 9 kategori
- Feature test klasifikasi + boundary + recommendation + CRUD/filter admin
- Evaluasi agreement berbasis confusion matrix (dataset assessor internal)

**Out of scope**
- Redesign total UI admin/public
- Implementasi RBAC kompleks/middleware role enterprise
- Migrasi otomatis dari seluruh data hardcoded lama

### 3) Keputusan Final

1. Klasifikasi mengikuti jurnal NNM dengan 9 kategori:
   - `hourglass`, `y_shape`, `inverted_triangle`, `spoon`, `rectangle`, `u`, `triangle`, `inverted_u`, `diamond`
2. API/internal tetap memakai key `y_shape` untuk kompatibilitas; label UI ditampilkan sebagai `Y`.
3. Boundary policy menggunakan operator strict (`>` / `<`) sesuai tabel jurnal.
   - Nilai tepat di batas (`0.97`, `1.24`, `0.89`, `1.20`) -> `undefined`.
4. UI existing dipertahankan (incremental changes only, no major redesign).
5. FK `fashion_items.fashion_category_id` menggunakan `restrictOnDelete`.
6. Manual body type di UI juga diperluas ke 9 kategori agar konsisten dengan hasil hitung.

### 4) Spesifikasi Aturan NNM (Jurnal)

Definisi rasio:
- `b = bust / waist`
- `h = hip / waist`

Ambang:
- `b_low = 0.97`
- `b_high = 1.24`
- `h_low = 0.89`
- `h_high = 1.20`

Aturan klasifikasi:
- `b > 1.24` dan `h > 1.20` -> `hourglass`
- `b > 1.24` dan `0.89 < h < 1.20` -> `y_shape`
- `b > 1.24` dan `h < 0.89` -> `inverted_triangle`
- `0.97 < b < 1.24` dan `h > 1.20` -> `spoon`
- `0.97 < b < 1.24` dan `0.89 < h < 1.20` -> `rectangle`
- `0.97 < b < 1.24` dan `h < 0.89` -> `u`
- `b < 0.97` dan `h > 1.20` -> `triangle`
- `b < 0.97` dan `0.89 < h < 1.20` -> `inverted_u`
- `b < 0.97` dan `h < 0.89` -> `diamond`
- selain kondisi di atas -> `undefined`

### 5) Struktur Database Final

#### A. `fashion_categories`
- `id` (PK)
- `name` string(100)
- `slug` string(120) unique
- `description` text nullable
- `is_active` boolean default true (indexed)
- `sort_order` unsignedInteger default 0 (indexed)
- timestamps
- index tambahan: `(is_active, sort_order)`

#### B. `fashion_items`
- `id` (PK)
- `fashion_category_id` foreignId -> `fashion_categories.id` (`restrictOnDelete`)
- `title` string(150) (indexed)
- `description` text nullable
- `body_type` string(50) (indexed; nilai 9 kategori NNM)
- `image_source` string (`upload|url`) (indexed)
- `image_path` string(255) nullable (mode upload)
- `image_url` string(2048) nullable (mode URL)
- `purchase_link` string(2048) nullable
- `is_active` boolean default true (indexed)
- `sort_order` unsignedInteger default 0 (indexed)
- timestamps
- index tambahan: `(fashion_category_id, is_active, sort_order)`, `(body_type, is_active)`

#### C. `morphotype_assessments`
- `id` (PK)
- `body_measurement_id` foreignId -> `body_measurements.id`
- `assessor_code` string(50) indexed
- `assessed_morphotype` string(50) indexed
- `predicted_morphotype` string(50) nullable indexed
- `bw_ratio` decimal(7,4) nullable
- `hw_ratio` decimal(7,4) nullable
- `notes` text nullable
- `assessed_at` timestamp nullable indexed
- timestamps
- unique: `(body_measurement_id, assessor_code)`

#### D. `morphotype_evaluation_runs`
- `id` (PK)
- `name` string(120)
- `dataset_label` string(120) nullable
- `total_samples` unsignedInteger
- `agreements` unsignedInteger
- `agreement_percentage` decimal(5,2)
- `confusion_matrix` json
- `baseline_percentage` decimal(5,2) nullable (benchmark referensi 81.90)
- `notes` text nullable
- `evaluated_at` timestamp indexed
- timestamps

### 6) Rencana Eksekusi (Wave)

**Wave A - Foundation**
- Migration 4 tabel baru.
- Model + relasi + casts + scope query dasar.
- FormRequest validasi kategori/item (slug unik, body type valid, upload/url conditional, URL valid `purchase_link`).

**Wave B - Admin Module**
- Controller admin kategori dan item (CRUD + filter/pencarian + sort/status).
- Route admin.
- Integrasi ke UI admin existing tanpa redesign besar.

**Wave C - Public Gallery Binding**
- `GalleryController@index` query item aktif dari DB.
- Refactor `gallery2` dari hardcoded JS ke data backend.
- Tombol beli kondisional jika `purchase_link` tersedia.
- Empty-state saat data kosong.

**Wave D - File Lifecycle**
- Upload disimpan ke storage publik terstandar.
- Gambar lama dipertahankan saat update tanpa file baru.
- File lokal dihapus ketika item upload dihapus.

**Wave E - NNM Alignment (9 kategori)**
- Refactor `MorphotypeService` ke rule jurnal NNM.
- Sinkronisasi `config/smartfit.php` (labels + recommendations + thresholds).
- Sinkronisasi mapping manual body type ke 9 kategori.

**Wave F - Testing & Evaluation**
- Feature test klasifikasi 9 kategori + kasus batas.
- Feature test recommendation kategori tambahan.
- Feature test admin CRUD/filter/validasi gallery.
- Implement confusion matrix + agreement % dari data assessor.

### 7) Verification Checklist

1. Jalankan migration, pastikan semua tabel baru terbentuk tanpa konflik.
2. Uji manual admin:
   - create/edit/delete kategori
   - create/edit/delete item (upload/url)
   - validasi `purchase_link`
   - filter keyword/kategori/body type/status
3. Uji manual `/gallery`:
   - data dari DB
   - item nonaktif tidak tampil
   - link beli muncul jika `purchase_link` tersedia
4. Uji API klasifikasi:
   - 9 kategori NNM sesuai rasio
   - boundary (`0.97`, `1.24`, `0.89`, `1.20`) menghasilkan `undefined`
5. Jalankan feature tests (classification + recommendation + admin gallery).
6. Jalankan evaluasi confusion matrix + agreement %, lalu dokumentasikan hasil run.

### 8) Catatan Evaluasi Jurnal

- Nilai 81.9% dari jurnal dipakai sebagai benchmark referensi, bukan target absolut lintas dataset.
- Agreement sistem internal dihitung dari dataset assessor internal yang terdokumentasi prosesnya.
