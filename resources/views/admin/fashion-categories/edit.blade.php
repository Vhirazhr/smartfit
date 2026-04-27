@extends('layouts.app_nonavbar')

@section('title', 'SMARTfit - Edit Fashion Category')

@section('content')
<div class="container" style="max-width: 760px; margin: 36px auto; padding: 0 16px;">
    <h2 style="margin-bottom: 12px;">Edit Fashion Category</h2>
    <p style="margin-top:0; color:#666;">Update category name, slug, and status.</p>

    @if($errors->any())
        <div style="padding:10px 12px; background:#fdecec; color:#8a2f2f; border-radius:8px; margin-bottom:12px;">
            <ul style="margin:0; padding-left:18px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.fashion-categories.update', $category->id) }}" style="background:#fff; border:1px solid #eee; border-radius:10px; padding:16px;">
        @csrf

        <div style="margin-bottom:10px;">
            <label for="name">Name *</label><br>
            <input id="name" type="text" name="name" value="{{ old('name', $category->name) }}" required maxlength="100" style="width:100%; padding:8px;">
        </div>

        <div style="margin-bottom:10px;">
            <label for="slug">Slug *</label><br>
            <input id="slug" type="text" name="slug" value="{{ old('slug', $category->slug) }}" required maxlength="120" style="width:100%; padding:8px;">
        </div>

        <div style="margin-bottom:10px;">
            <label for="description">Description</label><br>
            <textarea id="description" name="description" rows="4" style="width:100%; padding:8px;">{{ old('description', $category->description) }}</textarea>
        </div>

        <div style="display:flex; gap:14px; align-items:center; margin-bottom:14px;">
            <label for="sort_order">Sort order</label>
            <input id="sort_order" type="number" min="0" name="sort_order" value="{{ old('sort_order', $category->sort_order) }}" style="padding:8px; width:120px;">
            <input type="hidden" name="is_active" value="0">
            <label><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $category->is_active))> Active</label>
        </div>

        <div style="display:flex; gap:8px;">
            <button type="submit" style="padding:8px 12px;">Update Category</button>
            <a href="{{ route('admin.fashion-categories.index') }}">Back</a>
        </div>
    </form>
</div>
@endsection
