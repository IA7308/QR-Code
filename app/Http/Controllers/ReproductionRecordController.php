<?php

namespace App\Http\Controllers;

use App\Models\Sheep;
use App\Models\ReproductionRecord;
use App\Http\Requests\StoreReproductionRequest;
use App\Http\Requests\UpdateReproductionRequest; // (1) Impor Request Baru
use Illuminate\Support\Facades\Auth;

class ReproductionRecordController extends Controller
{
    /**
     * Menampilkan form input reproduksi (kawin).
     */
    public function create(Sheep $sheep)
    {
        if ($sheep->gender !== 'Betina') {
            return back()->with('error', 'Pencatatan reproduksi hanya untuk domba Betina.');
        }

        $potential_sires = Sheep::where('gender', 'Jantan')
                                ->orderBy('tag_number')
                                ->get(['id', 'tag_number']);

        return view('reproduction_records.create', compact('sheep', 'potential_sires'));
    }

    /**
     * Menyimpan data reproduksi baru.
     */
    public function store(StoreReproductionRequest $request, Sheep $sheep)
    {
        // ... (kode store sama seperti sebelumnya) ...
        if ($sheep->gender !== 'Betina') {
            abort(403, 'Hanya domba betina yang bisa reproduksi.');
        }

        $data = $request->validated();
        $data['female_sheep_id'] = $sheep->id;
        $data['assistance_user_id'] = Auth::id();

        ReproductionRecord::create($data);

        return redirect()->route('sheep.show', $sheep)
                         ->with('success', 'Data reproduksi berhasil dicatat.');
    }

    /**
     * (BARU) Menampilkan form edit reproduksi.
     */
    public function edit(ReproductionRecord $reproductionRecord)
    {
        // Ambil data domba induk terkait
        $sheep = $reproductionRecord->dam;

        // Ambil daftar pejantan
        $potential_sires = Sheep::where('gender', 'Jantan')
                                ->orderBy('tag_number')
                                ->get(['id', 'tag_number']);

        return view('reproduction_records.edit', compact('reproductionRecord', 'sheep', 'potential_sires'));
    }

    /**
     * (BARU) Memproses update data reproduksi.
     */
    public function update(UpdateReproductionRequest $request, ReproductionRecord $reproductionRecord)
    {
        $data = $request->validated();

        // Update data
        $reproductionRecord->update($data);

        return redirect()->route('sheep.show', $reproductionRecord->female_sheep_id)
                         ->with('success', 'Status reproduksi berhasil diperbarui!');
    }

    /**
     * Menghapus data reproduksi.
     */
    public function destroy(ReproductionRecord $reproductionRecord)
    {
        $sheepId = $reproductionRecord->female_sheep_id;
        $reproductionRecord->delete();

        return redirect()->route('sheep.show', $sheepId)
                         ->with('success', 'Data reproduksi dihapus.');
    }
}
