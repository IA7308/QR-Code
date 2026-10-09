<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSheepRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
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
            'tag_number' => 'required|string|max:255|unique:sheep,tag_number',
            'gender' => 'required|in:Jantan,Betina',
            'date_of_birth' => 'required|date',
            'birth_weight' => 'nullable|numeric|min:0',
            'category' => 'required|string|max:255',
            'type' => 'required|string|max:255',

            //Validasi Foreign Key
            'shelter_id' => 'required_if:placement_status,Internal|nullable|exists:shelters,id',
            'father_id' => 'nullable|exists:sheep,id',
            'mother_id' => 'nullable|exists:sheep,id',

            'purchase_price' => 'nullable|numeric|min:0',
            'is_pedigree' => 'nullable|boolean',
            'special_characteristics' => 'nullable|string',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',

            'placement_status' => 'required|in:Internal,Partner',
            'partner_id' => 'required_if:placement_status,Partner|nullable|exists:users,id',
        ];
    }

    public function messages()
    {
        return [
            'shelter_id.required_if' => 'Kandang wajib dipilih jika status penempatan adalah Internal.',
            'partner_id.required_if' => 'Nama Mitra wajib dipilih jika status penempatan adalah Mitra (Partner).',
        ];
    }
}
