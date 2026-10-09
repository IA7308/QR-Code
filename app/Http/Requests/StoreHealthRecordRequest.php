<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreHealthRecordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'record_date' => 'required|date|before_or_equal:today',
            'diagnosis' => 'required|string|max:255', // Contoh: "Infeksi Pencernaan"
            'treatment_details' => 'required|string',
            'medication_used' => 'nullable|string|max:255',
            'status' => 'required|in:Reported,Pending Treatment,In Treatment,Completed',

            // Validasi Array Gejala (Pivot)
            'symptoms' => 'required|array|min:1', // Wajib pilih minimal 1 gejala
            'symptoms.*' => 'exists:symptoms,id', // Pastikan ID gejala valid

            'photo' => 'nullable|image|max:2048',
        ];
    }
}
