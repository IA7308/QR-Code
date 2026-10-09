<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class StoreFeedTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                // Aturan Unik: Nama harus unik HANYA untuk user yang sedang login
                Rule::unique('feed_types')->where(function ($query) {
                    return $query->where('user_id', Auth::id());
                }),
            ],
            'unit' => 'required|string|max:50',       // misal: kg, ikat, karung
            'price_per_unit' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'name.unique' => 'Nama jenis pakan ini sudah ada di daftar Anda.',
        ];
    }
}
