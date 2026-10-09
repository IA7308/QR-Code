<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule; // (1) Impor Rule

class UpdateSheepRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Izinkan (bisa diubah jika pakai role)
    }

    protected function prepareForValidation()
    {
        if ($this->tag_number) {
            $this->merge([
                // Ubah input 'tag_number' (misal: "123")
                // menjadi format lengkap (misal: "JAS-123")
                'tag_number' => 'JAS-' . $this->tag_number,
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // (2) Aturan unique diubah untuk mengabaikan ID domba saat ini
            'tag_number' => [
                'required',
                'string',
                'max:255',
                Rule::unique('sheep')->ignore($this->sheep->id), // Abaikan ID domba ini
            ],
            'shelter_id' => 'required_if:placement_status,Internal|nullable|exists:shelters,id',
            'category' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'gender' => 'required|in:Jantan,Betina',
            'date_of_birth' => 'required|date',
            'birth_weight' => 'nullable|numeric|min:0',
            'father_id' => 'nullable|integer|exists:sheep,id',
            'mother_id' => 'nullable|integer|exists:sheep,id',
            'purchase_price' => 'nullable|numeric|min:0',

            // Validasi foto (hanya jika ada file baru yang di-upload)
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',

            'description' => 'nullable|string',

            'placement_status' => 'required|in:Internal,Partner',
            'partner_id' => 'required_if:placement_status,Partner|nullable|exists:users,id',
        ];
    }
}
