<?php

namespace App\Http\Controllers;

use App\Models\Sheep;
use App\Models\Symptom;
use App\Models\HealthRecord;
use App\Http\Requests\StoreHealthRecordRequest;
use Illuminate\Support\Facades\Auth;

class HealthRecordController extends Controller
{
    /**
     * Menampilkan form input kesehatan untuk domba tertentu.
     */
    public function create(Sheep $sheep)
    {
        // Ambil semua gejala untuk ditampilkan sebagai pilihan (checkbox/select)
        $symptoms = Symptom::orderBy('name')->get();

        return view('health_records.create', compact('sheep', 'symptoms'));
    }

    /**
     * Menyimpan data kesehatan dan relasi gejala.
     */
    public function store(StoreHealthRecordRequest $request, Sheep $sheep)
    {
        $data = $request->validated();

        // (1) Set Handler ID (User yang login) jika statusnya sudah ditangani
        // Atau biarkan null jika hanya pelaporan. Di sini kita set pencatat sebagai handler default.
        $data['handler_id'] = Auth::id();

        // (2) Upload Foto jika ada
        if ($request->hasFile('photo')) {
            $data['photo_path'] = $request->file('photo')->store('health_photos', 'public');
        }

        // (3) Simpan Record Utama
        $healthRecord = $sheep->healthRecords()->create($data);

        // (4) PENTING: Simpan Relasi Many-to-Many (Pivot) ke Gejala
        // attach() akan mengisi tabel 'health_record_symptom'
        $healthRecord->symptoms()->attach($request->input('symptoms'));

        return redirect()->route('sheep.show', $sheep)
                         ->with('success', 'Laporan kesehatan berhasil dicatat.');
    }

    /**
     * Menghapus catatan kesehatan.
     */
    public function destroy(HealthRecord $healthRecord)
    {
        $sheepId = $healthRecord->sheep_id;

        // Detach (hapus hubungan) gejala dulu sebelum hapus record
        $healthRecord->symptoms()->detach();
        $healthRecord->delete();

        return redirect()->route('sheep.show', $sheepId)
                         ->with('success', 'Catatan kesehatan dihapus.');
    }
}
