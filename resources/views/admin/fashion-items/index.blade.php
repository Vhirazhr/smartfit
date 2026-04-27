@extends('layouts.app_nonavbar')

@section('title', 'SMARTfit - Fashion Items')

@section('content')
<div class="container" style="max-width: 1280px; margin: 36px auto; padding: 0 16px;">
    <div style="display:flex; justify-content:space-between; align-items:center; gap:12px; margin-bottom:18px;">
        <div>
            <h2 style="margin:0;">Fashion Items</h2>
            <p style="margin:6px 0 0; color:#666;">Manage gallery items, body types, image source, and purchase links.</p>
        </div>
        <div style="display:flex; gap:8px;">
            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
            <a href="{{ route('admin.fashion-categories.index') }}">Categories</a>
            <a href="{{ route('admin.fashion-items.create') }}">+ Add Item</a>
        </div>
    </div>

    @if(session('success'))
        <div style="padding:10px 12px; background:#e8f8ef; color:#1f7a4c; border-radius:8px; margin-bottom:12px;">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div style="padding:10px 12px; background:#fdecec; color:#8a2f2f; border-radius:8px; margin-bottom:12px;">{{ session('error') }}</div>
    @endif

    <form method="GET" action="{{ route('admin.fashion-items.index') }}" style="display:flex; gap:8px; flex-wrap:wrap; margin-bottom:16px;">
        <input type="text" name="keyword" value="{{ $filters['keyword'] ?? '' }}" placeholder="Search title" style="padding:8px 10px; min-width:220px;">
        <select name="category_id" style="padding:8px 10px; min-width:180px;">
            <option value="">All categories</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" @selected((string)($filters['category_id'] ?? '') === (string)$category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
        <select name="body_type" style="padding:8px 10px; min-width:180px;">
            <option value="">All body types</option>
            @foreach($bodyTypes as $type)
                <option value="{{ $type }}" @selected(($filters['body_type'] ?? '') === $type)>{{ $labels[$type] ?? $type }}</option>
            @endforeach
        </select>
        <select name="is_active" style="padding:8px 10px;">
            <option value="">All status</option>
            <option value="1" @selected(($filters['is_active'] ?? '') === '1')>Active</option>
            <option value="0" @selected(($filters['is_active'] ?? '') === '0')>Inactive</option>
        </select>
        <button type="submit" style="padding:8px 12px;">Filter</button>
    </form>

    <div style="overflow:auto; background:#fff; border:1px solid #eee; border-radius:10px;">
        <table style="width:100%; border-collapse:collapse;">
            <thead>
                <tr style="background:#f9f9f9;">
                    <th style="text-align:left; padding:10px;">Image</th>
                    <th style="text-align:left; padding:10px;">Title</th>
                    <th style="text-align:left; padding:10px;">Category</th>
                    <th style="text-align:left; padding:10px;">Body Type</th>
                    <th style="text-align:left; padding:10px;">Source</th>
                    <th style="text-align:left; padding:10px;">Status</th>
                    <th style="text-align:left; padding:10px;">Sort</th>
                    <th style="text-align:left; padding:10px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                    <tr>
                        <td style="padding:10px; border-top:1px solid #f1f1f1;">
                            <img src="{{ $item->display_image_url }}" alt="{{ $item->title }}" style="width:56px; height:56px; object-fit:cover; border-radius:8px;">
                        </td>
                        <td style="padding:10px; border-top:1px solid #f1f1f1;">
                            <div>{{ $item->title }}</div>
                            @if($item->purchase_link)
                                <a href="{{ $item->purchase_link }}" target="_blank" rel="noopener" style="font-size:12px;">Purchase link</a>
                            @endif
                        </td>
                        <td style="padding:10px; border-top:1px solid #f1f1f1;">{{ $item->category?->name ?? '-' }}</td>
                        <td style="padding:10px; border-top:1px solid #f1f1f1;">{{ $labels[$item->body_type] ?? $item->body_type }}</td>
                        <td style="padding:10px; border-top:1px solid #f1f1f1; text-transform:capitalize;">{{ $item->image_source }}</td>
                        <td style="padding:10px; border-top:1px solid #f1f1f1;">{{ $item->is_active ? 'Active' : 'Inactive' }}</td>
                        <td style="padding:10px; border-top:1px solid #f1f1f1;">{{ $item->sort_order }}</td>
                        <td style="padding:10px; border-top:1px solid #f1f1f1; display:flex; gap:8px;">
                            <a href="{{ route('admin.fashion-items.edit', $item->id) }}">Edit</a>
                            <form method="POST" action="{{ route('admin.fashion-items.delete', $item->id) }}" onsubmit="return confirm('Delete this item?');">
                                @csrf
                                <button type="submit" style="background:none; border:none; color:#b33434; cursor:pointer; padding:0;">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="padding:16px; text-align:center; color:#777;">No fashion items found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:14px;">
        {{ $items->links() }}
    </div>
</div>
@endsection
