@extends('layouts.app_nonavbar')

@section('title', 'SMARTfit - Edit Fashion Item')

@section('content')
<div class="admin-wrapper">
    <aside class="admin-sidebar">
        <div class="sidebar-brand">
            <h2>SMARTfit</h2>
            <span>Admin Panel</span>
        </div>

        <nav class="sidebar-nav">
            <div class="sidebar-section">
                <p class="sidebar-section-title">Menu Utama</p>
                <ul class="sidebar-menu">
                    <li>
                        <a href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-chart-pie"></i>
                            Dashboard
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
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.fashion-items.index') }}" class="active">
                            <i class="fas fa-tshirt"></i>
                            Semua Fashion
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </aside>

    <main class="admin-main">
        <div class="top-bar">
            <div class="top-bar-left">
                <div class="top-bar-text">
                    <h1>Edit Fashion Item</h1>
                    <p>Update item data, image source, and purchase link.</p>
                </div>
            </div>
        </div>

        <div class="content-area">

            @if($errors->any())
                <div class="alert-box alert-error">
                    <i class="fas fa-exclamation-triangle"></i>
                    <div>
                        <strong>Periksa kembali:</strong>
                        <ul style="margin: 6px 0 0 16px;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="form-panel" style="max-width: 1000px; margin: 0 auto;">
                <div class="panel-header">
                    <i class="fas fa-tshirt"></i>
                    <h2>Edit Fashion Item</h2>
                </div>

                <form method="POST" action="{{ route('admin.fashion-items.update', $item->id) }}" enctype="multipart/form-data" class="fashion-form">
                    @csrf

                    {{-- SECTION 1 --}}
                    <div class="form-section">
                        <div class="form-section-header">
                            <div class="form-section-number">01</div>
                            <div class="form-section-title">
                                <h3>Informasi Dasar</h3>
                                <p>Judul, kategori, dan body type.</p>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>Category *</label>
                                <select name="fashion_category_id" required>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}"
                                            @selected(old('fashion_category_id', $item->fashion_category_id) == $category->id)>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Body Type *</label>
                                <select name="body_type" required>
                                    @foreach($bodyTypes as $type)
                                        <option value="{{ $type }}"
                                            @selected(old('body_type', $item->body_type) === $type)>
                                            {{ $labels[$type] ?? $type }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Title *</label>
                            <input type="text" name="title" value="{{ old('title', $item->title) }}" required>
                        </div>

                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" rows="4">{{ old('description', $item->description) }}</textarea>
                        </div>
                    </div>

                    {{-- SECTION 2 --}}
                    <div class="form-section">
                        <div class="form-section-header">
                            <div class="form-section-number">02</div>
                            <div class="form-section-title">
                                <h3>Image</h3>
                                <p>Pilih sumber gambar.</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>
                                <input type="radio" name="image_source" value="upload"
                                @checked(old('image_source', $item->image_source) === 'upload')>
                                Upload
                            </label>

                            <label style="margin-left:12px;">
                                <input type="radio" name="image_source" value="url"
                                @checked(old('image_source', $item->image_source) === 'url')>
                                URL
                            </label>
                        </div>

                        <input type="hidden" name="keep_existing_image"
                            value="{{ old('keep_existing_image', $item->image_source === 'upload' && $item->image_path ? 1 : 0) }}">

                        <div id="uploadField" class="form-group">
                            <label>Replace Image</label>
                            <input type="file" name="image_file" accept="image/*">
                        </div>

                        <div id="urlField" class="form-group">
                            <label>Image URL</label>
                            <input type="url" name="image_url"
                                value="{{ old('image_url', $item->image_url) }}">
                        </div>

                        <div class="form-group">
                            <label>Current Image</label><br>
                            <img src="{{ $item->display_image_url }}"
                                style="width:120px; height:120px; object-fit:cover; border-radius:12px;">
                        </div>
                    </div>

                    {{-- SECTION 3 --}}
                    <div class="form-section">
                        <div class="form-section-header">
                            <div class="form-section-number">03</div>
                            <div class="form-section-title">
                                <h3>Pengaturan</h3>
                                <p>Link, sort, dan status.</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Purchase Link</label>
                            <input type="url" name="purchase_link"
                                value="{{ old('purchase_link', $item->purchase_link) }}">
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>Sort Order</label>
                                <input type="number" name="sort_order"
                                    value="{{ old('sort_order', $item->sort_order) }}">
                            </div>

                            <div class="form-group">
                                <label>Status</label>
                                <input type="hidden" name="is_active" value="0">

                                <label class="toggle-row">
                                    <input type="checkbox" name="is_active" value="1"
                                        @checked(old('is_active', $item->is_active))>
                                    <span>Active</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('admin.fashion-items.index') }}" class="btn-cancel">Back</a>
                        <button type="submit" class="btn-submit">
                            Update Item
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </main>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
@endpush