@extends('layouts.app_nonavbar')

@section('title', 'SMARTfit - Fashion Categories')

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
                        <a href="{{ route('admin.fashion-categories.index') }}" class="active">
                            <i class="fas fa-tags"></i>
                            Kategori
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.fashion-items.index') }}">
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
                    <h1>Fashion Categories</h1>
                    <p>Manage custom categories for gallery items.</p>
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

        <div class="content-area">
            <div class="category-header">
                <div class="category-title">
                    <h1>Categories</h1>
                    <p>Kelola kategori fashion yang tampil di gallery.</p>
                </div>

                <div class="category-actions">
                    <a href="{{ route('admin.fashion-categories.create') }}">+ Add Category</a>
                </div>
            </div>

            @if(session('success'))
                <div class="alert-box alert-success">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert-box alert-error">
                    <i class="fas fa-exclamation-triangle"></i>
                    {{ session('error') }}
                </div>
            @endif

            <form method="GET" action="{{ route('admin.fashion-categories.index') }}" class="category-filter">
                <input type="text" name="keyword" value="{{ $filters['keyword'] ?? '' }}" placeholder="Search category name">

                <select name="status">
                    <option value="">All status</option>
                    <option value="1" @selected(($filters['status'] ?? '') === '1')>Active</option>
                    <option value="0" @selected(($filters['status'] ?? '') === '0')>Inactive</option>
                </select>

                <button type="submit">
                    <i class="fas fa-filter"></i>
                    Filter
                </button>
            </form>

            <div class="category-table-card">
                <table class="category-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Status</th>
                            <th>Sort</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($categories as $category)
                            <tr>
                                <td><strong>{{ $category->name }}</strong></td>
                                <td>{{ $category->slug }}</td>
                                <td>
                                    <span class="category-status {{ $category->is_active ? 'active' : 'inactive' }}">
                                        {{ $category->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>{{ $category->sort_order }}</td>
                                <td>
                                    <div class="category-row-actions">
                                        <a href="{{ route('admin.fashion-categories.edit', $category->id) }}" class="category-action-link edit">Edit</a>

                                        <form method="POST" action="{{ route('admin.fashion-categories.delete', $category->id) }}" onsubmit="return confirm('Delete this category?');">
                                            @csrf
                                            <button type="submit" class="category-action-link delete">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="category-empty">No categories found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="category-pagination">
                {{ $categories->links() }}
            </div>
        </div>
    </main>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
@endpush