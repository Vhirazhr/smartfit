@extends('layouts.app_nonavbar')

@section('title', 'SMARTfit - Create Fashion Item')

@section('content')
<div class="admin-wrapper">
    @include('admin.partials.sidebar', ['active' => 'fashion-items'])

    <main class="admin-main">
        <div class="top-bar">
            <div class="top-bar-left">
                <div class="top-bar-text">
                    <h1>Create Fashion Item</h1>
                    <p>Add a new item for gallery recommendation.</p>
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
                    <i class="fas fa-plus-circle"></i>
                    <h2>Create Fashion Item</h2>
                </div>

                <form method="POST" action="{{ route('admin.fashion-items.store') }}" enctype="multipart/form-data" class="fashion-form">
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
                                    <option value="">Select category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}"
                                            @selected(old('fashion_category_id') == $category->id)>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Body Type *</label>
                                <select name="body_type" required>
                                    <option value="">Select body type</option>
                                    @foreach($bodyTypes as $type)
                                        <option value="{{ $type }}"
                                            @selected(old('body_type') === $type)>
                                            {{ $labels[$type] ?? $type }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Title *</label>
                            <input type="text" name="title" value="{{ old('title') }}" required>
                        </div>

                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" rows="4">{{ old('description') }}</textarea>
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
                                @checked(old('image_source', 'upload') === 'upload')>
                                Upload
                            </label>

                            <label style="margin-left:12px;">
                                <input type="radio" name="image_source" value="url"
                                @checked(old('image_source') === 'url')>
                                URL
                            </label>
                        </div>

                        <div id="uploadField" class="form-group">
                            <label>Image File *</label>
                            <input type="file" name="image_file" accept="image/*">
                        </div>

                        <div id="urlField" class="form-group">
                            <label>Image URL *</label>
                            <input type="url" name="image_url" value="{{ old('image_url') }}">
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
                            <input type="url" name="purchase_link" value="{{ old('purchase_link') }}">
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>Sort Order</label>
                                <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}">
                            </div>

                            <div class="form-group">
                                <label>Status</label>
                                <input type="hidden" name="is_active" value="0">

                                <label class="toggle-row">
                                    <input type="checkbox" name="is_active" value="1"
                                        @checked(old('is_active', 1))>
                                    <span>Active</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('admin.fashion-items.index') }}" class="btn-cancel">Back</a>
                        <button type="submit" class="btn-submit">
                            Save Item
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
