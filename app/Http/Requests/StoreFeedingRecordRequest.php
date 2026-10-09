<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFeedingRecordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'shelter_id' => 'required|exists:shelters,id',
            'date' => 'required|date|before_or_equal:today',
            'time_morning' => 'nullable|date_format:H:i',
            'time_evening' => 'nullable|date_format:H:i|after:time_morning',

            // Dynamic Fields untuk Pakan (Pagi dan Sore)
            'feed_types' => 'required|array|min:1',
            'feed_types.*.id' => 'required|exists:feed_types,id',
            // PENTING: Minimal salah satu porsi harus diisi (dicek di withValidator)
            'feed_types.*.morning' => 'nullable|numeric|min:0',
            'feed_types.*.evening' => 'nullable|numeric|min:0',
        ];
    }

    // PENTING: Tambahkan validasi untuk memastikan minimal ada satu kuantitas yang diisi
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $hasQuantity = false;
            foreach ($this->input('feed_types') as $feed) {
                // Cek jika kuantitas pagi ATAU sore diisi
                if (!empty($feed['morning']) || !empty($feed['evening'])) {
                    $hasQuantity = true;
                    break;
                }
            }

            if (!$hasQuantity) {
                $validator->errors()->add('feed_types', 'Anda wajib mengisi kuantitas (pagi atau sore) minimal pada satu jenis pakan.');
            }
        });
    }
}
