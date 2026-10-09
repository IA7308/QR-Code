<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateShelterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        $shelterId = $this->route('shelter')->id; // Ambil ID dari rute

        return [
            // Nama harus unik, tapi abaikan nama kandang yang sedang diedit
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('shelters', 'name')->ignore($shelterId),
            ],
            'capacity' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ];
    }
}
