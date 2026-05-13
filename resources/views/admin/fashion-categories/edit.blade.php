@extends('layouts.app_nonavbar')

@section('title', 'SMARTfit - Edit Fashion Category')

@section('content')
<div class="admin-wrapper">
    @include('admin.partials.sidebar', ['active' => 'fashion-categories'])

    <main class="admin-main">
        <div class="top-bar">
            <div class="top-bar-left">
                <div class="top-bar-text">
                    <h1>Edit Category</h1>
                    <p>Update category name, slug, and status.</p>
                </div>
            </div>
        </div>

        <div class="content-area">
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

            <div class="form-panel category-form-panel">
                <div class="panel-header">
                    <i class="fas fa-tags"></i>
                    <h2>Edit Fashion Category</h2>
                </div>

                <form method="POST" action="{{ route('admin.fashion-categories.update', $category->id) }}" class="fashion-form">
                    @csrf

                    <div class="form-section">
                        <div class="form-section-header">
                            <div class="form-section-number">01</div>
                            <div class="form-section-title">
                                <h3>Informasi Kategori</h3>
                                <p>Update nama kategori dan slug.</p>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="name">Name <span class="required">*</span></label>
                                <input id="name" type="text" name="name" value="{{ old('name', $category->name) }}" required maxlength="100">
                            </div>

                            <div class="form-group">
                                <label for="slug">Slug <span class="required">*</span></label>
                                <input id="slug" type="text" name="slug" value="{{ old('slug', $category->slug) }}" required maxlength="120">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea id="description" name="description" rows="4">{{ old('description', $category->description) }}</textarea>
                        </div>
                    </div>

                    <div class="form-section">
                        <div class="form-section-header">
                            <div class="form-section-number">02</div>
                            <div class="form-section-title">
                                <h3>Pengaturan</h3>
                                <p>Atur urutan dan status kategori.</p>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="sort_order">Sort order</label>
                                <input id="sort_order" type="number" min="0" name="sort_order" value="{{ old('sort_order', $category->sort_order) }}">
                            </div>

                            <div class="form-group">
                                <label>Status</label>
                                <input type="hidden" name="is_active" value="0">

                                <label class="toggle-row">
                                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $category->is_active))>
                                    <span>Active</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('admin.fashion-categories.index') }}" class="btn-cancel">Back</a>
                        <button type="submit" class="btn-submit category-submit">
                            <i class="fas fa-save"></i>
                            Update Category
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
