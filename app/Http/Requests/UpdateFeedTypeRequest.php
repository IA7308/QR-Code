<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UpdateFeedTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Pastikan user pemilik data yang boleh update (sudah dicek di controller, tapi double check oke)
        return $this->feed_type->user_id === Auth::id() || Auth::user()->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                // Unik per user, tapi abaikan data yang sedang diedit ini
                Rule::unique('feed_types')->where(function ($query) {
                    return $query->where('user_id', $this->feed_type->user_id);
                })->ignore($this->feed_type->id),
            ],
            'unit' => 'required|string|max:50',
            'price_per_unit' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ];
    }
}
