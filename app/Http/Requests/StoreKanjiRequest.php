<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreKanjiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'character' => 'required|string|max:10|unique:kanjis,character',
            'meaning' => 'required|string|max:255',
            'on_reading' => 'nullable|string|max:100',
            'kun_reading' => 'nullable|string|max:100',
            'level' => 'required|in:N5,N4,N3,N2,N1',
            'stroke_count' => 'required|integer|min:1|max:30',
            'radical' => 'nullable|string|max:50',
            'examples' => 'nullable|string',
        ];
    }
}
