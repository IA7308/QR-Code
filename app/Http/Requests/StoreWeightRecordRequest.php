<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWeightRecordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'weighing_date' => 'required|date|before_or_equal:today',
            'weight' => 'required|numeric|min:0.1|max:200', // Validasi berat wajar (kg)
            'notes' => 'nullable|string|max:255',
        ];
    }
}
