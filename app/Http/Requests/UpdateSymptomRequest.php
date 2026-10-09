<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSymptomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $symptomId = $this->route('symptom')->id;

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('symptoms', 'name')->ignore($symptomId) // Abaikan ID ini saat cek unik
            ],
            'description' => 'nullable|string',
        ];
    }
}
