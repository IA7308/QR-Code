<?php

namespace App\Http\Controllers;

use App\Models\Sheep;
use App\Models\WeightRecord;
use App\Http\Requests\StoreWeightRecordRequest;
use Illuminate\Support\Facades\Auth;

class WeightRecordController extends Controller
{
    /**
     * Menampilkan form untuk menambah data timbangan domba tertentu.
     */
    public function create(Sheep $sheep)
    {
        return view('weight_records.create', compact('sheep'));
    }

    /**
     * Menyimpan data timbangan baru.
     */
    public function store(StoreWeightRecordRequest $request, Sheep $sheep)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();

        // Simpan data melalui relasi untuk otomatis mengisi sheep_id
        $sheep->weightRecords()->create($data);

        return redirect()->route('sheep.show', $sheep)
                         ->with('success', 'Data timbangan berhasil ditambahkan!');
    }

    /**
     * Menghapus data timbangan.
     * PERBAIKAN: Ubah nama parameter menjadi $weight (sesuai resource 'sheep.weights')
     */
    public function destroy(WeightRecord $weight)
    {
        // Ambil ID domba sebelum dihapus untuk redirect
        $sheepId = $weight->sheep_id;

        $weight->delete();

        // Gunakan array ['sheep' => $id] agar lebih eksplisit dan aman
        return redirect()->route('sheep.show', ['sheep' => $sheepId])
                         ->with('success', 'Data timbangan berhasil dihapus.');
    }
}
