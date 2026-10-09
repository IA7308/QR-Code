<?php

namespace App\Http\Controllers;

use App\Models\Shelter;
use App\Models\FeedType;
use App\Models\FeedingRecord;
use App\Http\Requests\StoreFeedingRecordRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class FeedingRecordController extends Controller
{
    // ... (method index tetap sama) ...
    public function index(Request $request)
    {
        // Mulai query dasar
        $query = FeedingRecord::with(['shelter', 'user', 'feedTypes']);

        // Logika Filter: Tanggal Mulai
        if ($request->filled('start_date')) {
            $query->whereDate('date', '>=', $request->start_date);
        }

        // Logika Filter: Tanggal Akhir
        if ($request->filled('end_date')) {
            $query->whereDate('date', '<=', $request->end_date);
        }

        // Logika Filter: Kandang
        if ($request->filled('shelter_id')) {
            $query->where('shelter_id', $request->shelter_id);
        }

        // --- FILTER USER (OPSIONAL, AGAR MITRA HANYA LIHAT DATA SENDIRI) ---
        $user = Auth::user();
        if ($user->role === 'mitra') {
            $query->where('user_id', $user->id);
        } elseif ($request->filled('partner_id') && $request->partner_id !== 'all') {
            $query->where('user_id', $request->partner_id);
        } elseif (!$request->filled('partner_id')) {
             // Default Admin lihat punya sendiri jika tidak filter
             $query->where('user_id', $user->id);
        }

        $feedingRecords = $query->orderByDesc('date')
                                ->paginate(10)
                                ->withQueryString();

        $shelters = Shelter::orderBy('name')->get(['id', 'name']);

        // Kirim partners untuk dropdown filter Admin (jika perlu)
        $partners = \App\Models\User::where('role', 'mitra')->get();

        return view('feeding_records.index', compact('feedingRecords', 'shelters', 'partners'));
    }

    /**
     * Form Create: Terapkan Isolasi Data Pakan Disini
     */
    public function create()
    {
        $user = Auth::user();

        // 1. Filter Kandang (Sama seperti sebelumnya)
        $sheltersQuery = Shelter::orderBy('name');
        if ($user->role === 'mitra') {
            $sheltersQuery->where('user_id', $user->id);
        }
        $shelters = $sheltersQuery->get(['id', 'name']);

        // 2. FILTER JENIS PAKAN (SOLUSI INTI)
        // Hanya ambil jenis pakan yang 'user_id'-nya sama dengan user yang login
        $feedTypes = FeedType::where('user_id', $user->id)
            ->orderBy('name')
            ->get(['id', 'name', 'unit', 'price_per_unit']);

        // Jika list kosong (user belum input master data pakan), berikan notifikasi atau handling di view

        return view('feeding_records.create', compact('shelters', 'feedTypes'));
    }

    // ... (method store tetap sama) ...
    public function store(StoreFeedingRecordRequest $request)
    {
        $data = $request->validated();

        $exists = FeedingRecord::where('shelter_id', $data['shelter_id'])
                               ->where('date', $data['date'])
                               ->exists();

        if ($exists) {
            return back()->withInput()->with('error', 'Catatan pakan untuk kandang ini pada tanggal ini sudah ada!');
        }

        $data['user_id'] = Auth::id();

        $record = FeedingRecord::create($data);

        $pivotData = $this->preparePivotData($data['feed_types']);
        $record->feedTypes()->attach($pivotData);

        return redirect()->route('feeding-records.index')->with('success', 'Pencatatan pakan harian berhasil!');
    }

    // ... (method show tetap sama) ...
    public function show(FeedingRecord $feedingRecord)
    {
        $feedingRecord->load(['shelter', 'user', 'feedTypes']);
        return view('feeding_records.show', compact('feedingRecord'));
    }

    /**
     * Form Edit: Terapkan Isolasi Data Pakan Disini Juga
     */
    public function edit(FeedingRecord $feedingRecord)
    {
        // Security Check
        $user = Auth::user();
        if ($user->role === 'mitra' && $feedingRecord->user_id !== $user->id) {
            abort(403);
        }

        // 1. Filter Kandang
        $sheltersQuery = Shelter::orderBy('name');
        if ($user->role === 'mitra') {
            $sheltersQuery->where('user_id', $user->id);
        }
        $shelters = $sheltersQuery->get(['id', 'name']);

        // 2. FILTER JENIS PAKAN
        $feedTypes = FeedType::where('user_id', $user->id)
            ->orderBy('name')
            ->get(['id', 'name', 'unit', 'price_per_unit']);

        $feedingRecord->load('feedTypes');

        return view('feeding_records.edit', compact('feedingRecord', 'shelters', 'feedTypes'));
    }

    // ... (sisa method update, destroy, preparePivotData tetap sama) ...
    public function update(StoreFeedingRecordRequest $request, FeedingRecord $feedingRecord)
    {
        $data = $request->validated();

        $exists = FeedingRecord::where('shelter_id', $data['shelter_id'])
                               ->where('date', $data['date'])
                               ->where('id', '!=', $feedingRecord->id)
                               ->exists();

        if ($exists) {
            return back()->withInput()->with('error', 'Catatan pakan untuk kandang ini pada tanggal ini sudah ada!');
        }

        $feedingRecord->update([
            'shelter_id' => $data['shelter_id'],
            'date' => $data['date'],
            'time_morning' => $data['time_morning'],
            'time_evening' => $data['time_evening'],
        ]);

        $pivotData = $this->preparePivotData($data['feed_types']);
        $feedingRecord->feedTypes()->sync($pivotData);

        return redirect()->route('feeding-records.index')->with('success', 'Data pakan berhasil diperbarui!');
    }

    public function destroy(FeedingRecord $feedingRecord)
    {
        $feedingRecord->feedTypes()->detach();
        $feedingRecord->delete();

        return redirect()->route('feeding-records.index')->with('success', 'Data pakan berhasil dihapus.');
    }

    private function preparePivotData($feedTypesInput)
    {
        return collect($feedTypesInput)->mapWithKeys(function ($feed) {
            $morning = (float) ($feed['morning'] ?? 0);
            $evening = (float) ($feed['evening'] ?? 0);

            if ($morning > 0 || $evening > 0) {
                 return [
                    $feed['id'] => [
                        'quantity_morning' => $morning,
                        'quantity_evening' => $evening
                    ]
                ];
            }
            return [];
        })->toArray();
    }
}
