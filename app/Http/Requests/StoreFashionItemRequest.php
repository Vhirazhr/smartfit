<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreFashionItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $bodyTypes = config('smartfit.body_types', []);

        return [
            'fashion_category_id' => ['required', 'integer', 'exists:fashion_categories,id'],
            'title' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
            'body_type' => ['required', Rule::in($bodyTypes)],
            'image_source' => ['required', Rule::in(['upload', 'url'])],
            'image_file' => ['required_if:image_source,upload', 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'image_url' => ['required_if:image_source,url', 'nullable', 'url', 'max:2048'],
            'purchase_link' => ['nullable', 'url', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
