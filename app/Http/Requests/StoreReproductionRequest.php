<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreReproductionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Pejantan (Male) harus ada di tabel sheep DAN gender-nya harus Jantan
            'male_sheep_id' => [
                'required',
                'exists:sheep,id',
                Rule::exists('sheep', 'id')->where(function ($query) {
                    return $query->where('gender', 'Jantan');
                }),
            ],

            'mating_date' => 'required|date|before_or_equal:today',

            'status' => 'required|in:Planned,Mated,Pregnant,Delivered,Failed',

            // Opsional (untuk update nanti atau jika data lengkap langsung diinput)
            'actual_delivery_date' => 'nullable|date|after_or_equal:mating_date',
            'offspring_count' => 'nullable|integer|min:0',
            'notes' => 'nullable|string|max:1000',
        ];
    }
}
