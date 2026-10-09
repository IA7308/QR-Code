<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreShelterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:shelters,name', // Wajib unik
            'capacity' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ];
    }
}
