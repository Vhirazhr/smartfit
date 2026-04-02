<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClassifyMorphotypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'bust' => ['required', 'numeric', 'gt:0'],
            'waist' => ['required', 'numeric', 'gt:0'],
            'hip' => ['required', 'numeric', 'gt:0'],
        ];
    }
}