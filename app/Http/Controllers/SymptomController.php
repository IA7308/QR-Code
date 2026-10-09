<?php

namespace App\Http\Controllers;

use App\Models\Symptom;
use App\Http\Requests\StoreSymptomRequest;
use App\Http\Requests\UpdateSymptomRequest;

class SymptomController extends Controller
{
    /**
     * Menampilkan daftar gejala.
     */
    public function index()
    {
        $symptoms = Symptom::orderBy('name')->paginate(10);
        return view('symptoms.index', compact('symptoms'));
    }

    /**
     * Form tambah gejala.
     */
    public function create()
    {
        return view('symptoms.create');
    }

    /**
     * Simpan gejala baru.
     */
    public function store(StoreSymptomRequest $request)
    {
        Symptom::create($request->validated());
        return redirect()->route('symptoms.index')->with('success', 'Gejala baru berhasil ditambahkan!');
    }

    /**
     * Form edit gejala.
     */
    public function edit(Symptom $symptom)
    {
        return view('symptoms.edit', compact('symptom'));
    }

    /**
     * Update gejala.
     */
    public function update(UpdateSymptomRequest $request, Symptom $symptom)
    {
        $symptom->update($request->validated());
        return redirect()->route('symptoms.index')->with('success', 'Data gejala berhasil diperbarui!');
    }

    /**
     * Hapus gejala.
     */
    public function destroy(Symptom $symptom)
    {
        // Nanti kita bisa tambahkan cek: jangan hapus jika sudah dipakai di HealthRecord
        $symptom->delete();
        return redirect()->route('symptoms.index')->with('success', 'Gejala berhasil dihapus.');
    }
}
