@extends('layouts.app_nonavbar')

@section('title', 'SMARTfit - Fashion Categories')

@section('content')
<div class="container" style="max-width: 1100px; margin: 36px auto; padding: 0 16px;">
    <div style="display:flex; justify-content:space-between; align-items:center; gap:12px; margin-bottom:18px;">
        <div>
            <h2 style="margin:0;">Fashion Categories</h2>
            <p style="margin:6px 0 0; color:#666;">Manage custom categories for gallery items.</p>
        </div>
        <div style="display:flex; gap:8px;">
            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
            <a href="{{ route('admin.fashion-items.index') }}">Fashion Items</a>
            <a href="{{ route('admin.fashion-categories.create') }}">+ Add Category</a>
        </div>
    </div>

    @if(session('success'))
        <div style="padding:10px 12px; background:#e8f8ef; color:#1f7a4c; border-radius:8px; margin-bottom:12px;">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div style="padding:10px 12px; background:#fdecec; color:#8a2f2f; border-radius:8px; margin-bottom:12px;">{{ session('error') }}</div>
    @endif

    <form method="GET" action="{{ route('admin.fashion-categories.index') }}" style="display:flex; gap:8px; flex-wrap:wrap; margin-bottom:16px;">
        <input type="text" name="keyword" value="{{ $filters['keyword'] ?? '' }}" placeholder="Search category name" style="padding:8px 10px; min-width:220px;">
        <select name="status" style="padding:8px 10px;">
            <option value="">All status</option>
            <option value="1" @selected(($filters['status'] ?? '') === '1')>Active</option>
            <option value="0" @selected(($filters['status'] ?? '') === '0')>Inactive</option>
        </select>
        <button type="submit" style="padding:8px 12px;">Filter</button>
    </form>

    <div style="overflow:auto; background:#fff; border:1px solid #eee; border-radius:10px;">
        <table style="width:100%; border-collapse:collapse;">
            <thead>
                <tr style="background:#f9f9f9;">
                    <th style="text-align:left; padding:10px;">Name</th>
                    <th style="text-align:left; padding:10px;">Slug</th>
                    <th style="text-align:left; padding:10px;">Status</th>
                    <th style="text-align:left; padding:10px;">Sort</th>
                    <th style="text-align:left; padding:10px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                    <tr>
                        <td style="padding:10px; border-top:1px solid #f1f1f1;">{{ $category->name }}</td>
                        <td style="padding:10px; border-top:1px solid #f1f1f1;">{{ $category->slug }}</td>
                        <td style="padding:10px; border-top:1px solid #f1f1f1;">{{ $category->is_active ? 'Active' : 'Inactive' }}</td>
                        <td style="padding:10px; border-top:1px solid #f1f1f1;">{{ $category->sort_order }}</td>
                        <td style="padding:10px; border-top:1px solid #f1f1f1; display:flex; gap:8px;">
                            <a href="{{ route('admin.fashion-categories.edit', $category->id) }}">Edit</a>
                            <form method="POST" action="{{ route('admin.fashion-categories.delete', $category->id) }}" onsubmit="return confirm('Delete this category?');">
                                @csrf
                                <button type="submit" style="background:none; border:none; color:#b33434; cursor:pointer; padding:0;">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding:16px; text-align:center; color:#777;">No categories found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:14px;">
        {{ $categories->links() }}
    </div>
</div>
@endsection
