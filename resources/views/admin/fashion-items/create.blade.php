@extends('layouts.app_nonavbar')

@section('title', 'SMARTfit - Create Fashion Item')

@section('content')
<div class="container" style="max-width: 880px; margin: 36px auto; padding: 0 16px;">
    <h2 style="margin-bottom: 12px;">Create Fashion Item</h2>
    <p style="margin-top:0; color:#666;">Add a new item for dynamic gallery recommendation.</p>

    @if($errors->any())
        <div style="padding:10px 12px; background:#fdecec; color:#8a2f2f; border-radius:8px; margin-bottom:12px;">
            <ul style="margin:0; padding-left:18px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.fashion-items.store') }}" enctype="multipart/form-data" style="background:#fff; border:1px solid #eee; border-radius:10px; padding:16px;">
        @csrf

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
            <div>
                <label for="fashion_category_id">Category *</label><br>
                <select id="fashion_category_id" name="fashion_category_id" required style="width:100%; padding:8px;">
                    <option value="">Select category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" @selected(old('fashion_category_id') == $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="body_type">Body type *</label><br>
                <select id="body_type" name="body_type" required style="width:100%; padding:8px;">
                    <option value="">Select body type</option>
                    @foreach($bodyTypes as $type)
                        <option value="{{ $type }}" @selected(old('body_type') === $type)>{{ $labels[$type] ?? $type }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div style="margin-top:10px;">
            <label for="title">Title *</label><br>
            <input id="title" type="text" name="title" value="{{ old('title') }}" required maxlength="150" style="width:100%; padding:8px;">
        </div>

        <div style="margin-top:10px;">
            <label for="description">Description</label><br>
            <textarea id="description" name="description" rows="4" style="width:100%; padding:8px;">{{ old('description') }}</textarea>
        </div>

        <div style="margin-top:12px;">
            <label>Image source *</label><br>
            <label><input type="radio" name="image_source" value="upload" @checked(old('image_source', 'upload') === 'upload')> Upload</label>
            <label style="margin-left:12px;"><input type="radio" name="image_source" value="url" @checked(old('image_source') === 'url')> URL</label>
        </div>

        <div id="uploadField" style="margin-top:10px;">
            <label for="image_file">Image file *</label><br>
            <input id="image_file" type="file" name="image_file" accept="image/*" style="width:100%; padding:8px;">
        </div>

        <div id="urlField" style="margin-top:10px;">
            <label for="image_url">Image URL *</label><br>
            <input id="image_url" type="url" name="image_url" value="{{ old('image_url') }}" placeholder="https://example.com/image.jpg" style="width:100%; padding:8px;">
        </div>

        <div style="margin-top:10px;">
            <label for="purchase_link">Purchase link</label><br>
            <input id="purchase_link" type="url" name="purchase_link" value="{{ old('purchase_link') }}" placeholder="https://store.example/product" style="width:100%; padding:8px;">
        </div>

        <div style="display:flex; gap:14px; align-items:center; margin-top:12px;">
            <label for="sort_order">Sort order</label>
            <input id="sort_order" type="number" min="0" name="sort_order" value="{{ old('sort_order', 0) }}" style="padding:8px; width:120px;">
            <input type="hidden" name="is_active" value="0">
            <label><input type="checkbox" name="is_active" value="1" @checked(old('is_active', 1))> Active</label>
        </div>

        <div style="display:flex; gap:8px; margin-top:16px;">
            <button type="submit" style="padding:8px 12px;">Save Item</button>
            <a href="{{ route('admin.fashion-items.index') }}">Back</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        const uploadField = document.getElementById('uploadField');
        const urlField = document.getElementById('urlField');
        const radios = document.querySelectorAll('input[name="image_source"]');

        function syncSourceFields() {
            const source = document.querySelector('input[name="image_source"]:checked')?.value || 'upload';
            uploadField.style.display = source === 'upload' ? 'block' : 'none';
            urlField.style.display = source === 'url' ? 'block' : 'none';
        }

        radios.forEach((radio) => radio.addEventListener('change', syncSourceFields));
        syncSourceFields();
    })();
</script>
@endpush
