@extends('layouts.app_nonavbar')

@section('title', 'SMARTfit - Fashion Items')

@section('content')
<div class="admin-wrapper">
    @include('admin.partials.sidebar', ['active' => 'fashion-items'])

    <main class="admin-main">
        <div class="top-bar">
            <div class="top-bar-left">
                <div class="top-bar-text">
                    <h1>Fashion Items</h1>
                    <p>Manage gallery items, body types, image source, and purchase links.</p>
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
                    <h1>All Fashion Items</h1>
                    <p>Kelola semua item fashion yang tersimpan.</p>
                </div>

                <div class="category-actions">
                    <a href="{{ route('admin.fashion-items.create') }}">+ Add Item</a>
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

            <form method="GET" action="{{ route('admin.fashion-items.index') }}" class="category-filter fashion-items-filter">
                <input type="text" name="keyword" value="{{ $filters['keyword'] ?? '' }}" placeholder="Search title">

                <select name="category_id">
                    <option value="">All categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" @selected((string)($filters['category_id'] ?? '') === (string)$category->id)>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>

                <select name="body_type">
                    <option value="">All body types</option>
                    @foreach($bodyTypes as $type)
                        <option value="{{ $type }}" @selected(($filters['body_type'] ?? '') === $type)>
                            {{ $labels[$type] ?? $type }}
                        </option>
                    @endforeach
                </select>

                <select name="is_active">
                    <option value="">All status</option>
                    <option value="1" @selected(($filters['is_active'] ?? '') === '1')>Active</option>
                    <option value="0" @selected(($filters['is_active'] ?? '') === '0')>Inactive</option>
                </select>

                <button type="submit">
                    <i class="fas fa-filter"></i>
                    Filter
                </button>
            </form>

            <div class="category-table-card">
                <table class="category-table fashion-items-table">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Body Type</th>
                            <th>Source</th>
                            <th>Status</th>
                            <th>Sort</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($items as $item)
                            <tr>
                                <td>
                                    <img class="fashion-item-thumb" src="{{ $item->display_image_url }}" alt="{{ $item->title }}">
                                </td>

                                <td>
                                    <strong>{{ $item->title }}</strong>

                                    @if($item->purchase_link)
                                        <div>
                                            <a href="{{ $item->purchase_link }}" target="_blank" rel="noopener" class="purchase-link">
                                                Purchase link
                                            </a>
                                        </div>
                                    @endif
                                </td>

                                <td>{{ $item->category?->name ?? '-' }}</td>

                                <td>
                                    <span class="mini-badge">
                                        {{ $labels[$item->body_type] ?? $item->body_type }}
                                    </span>
                                </td>

                                <td>
                                    <span class="mini-badge neutral">
                                        {{ ucfirst($item->image_source) }}
                                    </span>
                                </td>

                                <td>
                                    <span class="category-status {{ $item->is_active ? 'active' : 'inactive' }}">
                                        {{ $item->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>

                                <td>{{ $item->sort_order }}</td>

                                <td>
                                    <div class="category-row-actions">
                                        <a href="{{ route('admin.fashion-items.edit', $item->id) }}" class="category-action-link edit">
                                            Edit
                                        </a>

                                        <form method="POST" action="{{ route('admin.fashion-items.delete', $item->id) }}" onsubmit="return confirm('Delete this item?');">
                                            @csrf
                                            <button type="submit" class="category-action-link delete">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="category-empty">
                                    No fashion items found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="category-pagination">
                {{ $items->links() }}
            </div>
        </div>
    </main>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
@endpush
