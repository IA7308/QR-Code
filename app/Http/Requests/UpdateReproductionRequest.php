<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReproductionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'male_sheep_id' => 'required|exists:sheep,id',
            'mating_date' => 'required|date|before_or_equal:today',
            'status' => 'required|in:Planned,Mated,Pregnant,Delivered,Failed',

            // Validasi Kondisional:
            // Jika status 'Delivered', maka Tanggal Lahir & Jumlah Anak WAJIB diisi
            'actual_delivery_date' => 'required_if:status,Delivered|nullable|date|after_or_equal:mating_date',
            'offspring_count' => 'required_if:status,Delivered|nullable|integer|min:0',

            'notes' => 'nullable|string|max:1000',
        ];
    }

    public function messages()
    {
        return [
            'actual_delivery_date.required_if' => 'Tanggal lahir wajib diisi jika status sudah Melahirkan (Delivered).',
            'offspring_count.required_if' => 'Jumlah anak wajib diisi jika status sudah Melahirkan (Delivered).',
        ];
    }
}
