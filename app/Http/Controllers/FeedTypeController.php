<?php

namespace App\Http\Controllers;

use App\Models\FeedType;
use App\Models\User; // Import Model User
use App\Http\Requests\StoreFeedTypeRequest;
use App\Http\Requests\UpdateFeedTypeRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request; // Import Request

class FeedTypeController extends Controller
{
    /**
     * Menampilkan daftar jenis pakan.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Query dasar dengan eager loading user (pemilik data)
        $query = FeedType::with('user');

        // --- LOGIKA FILTER USER (Konsisten dengan modul lain) ---
        if ($user->role === 'mitra') {
            // SKENARIO 1: MITRA
            // Hanya melihat data miliknya sendiri
            $query->where('user_id', $user->id);
        } else {
            // SKENARIO 2: ADMIN
            if ($request->filled('partner_id')) {
                // Cek apakah Admin memilih "Semua Data"
                if ($request->partner_id === 'all') {
                    // Tampilkan Semua (Tanpa filter user_id)
                } else {
                    // Filter Mitra Tertentu
                    $query->where('user_id', $request->partner_id);
                }
            } else {
                // DEFAULT (Jika tidak ada filter): Tampilkan Data Admin Saja
                $query->where('user_id', $user->id);
            }
        }

        // Search Filter
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Sorting
        $query->orderBy('name');

        $feedTypes = $query->paginate(10)->withQueryString();

        // Ambil daftar mitra untuk dropdown filter (Khusus Admin)
        $partners = [];
        if ($user->role !== 'mitra') {
            $partners = User::where('role', 'mitra')->orderBy('name')->get(['id', 'name']);
        }

        return view('feed_types.index', compact('feedTypes', 'partners'));
    }

    /**
     * Form tambah jenis pakan.
     */
    public function create()
    {
        return view('feed_types.create');
    }

    /**
     * Simpan jenis pakan baru.
     */
    public function store(StoreFeedTypeRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id(); // Assign pemilik data

        FeedType::create($data);

        return redirect()->route('feed-types.index')->with('success', 'Jenis pakan berhasil ditambahkan!');
    }

    /**
     * Form edit jenis pakan.
     */
    public function edit(FeedType $feedType)
    {
        // Security Check
        $this->authorizeAccess($feedType);

        return view('feed_types.edit', compact('feedType'));
    }

    /**
     * Update jenis pakan.
     */
    public function update(UpdateFeedTypeRequest $request, FeedType $feedType)
    {
        // Security Check
        $this->authorizeAccess($feedType);

        $feedType->update($request->validated());

        return redirect()->route('feed-types.index')->with('success', 'Data pakan berhasil diperbarui!');
    }

    /**
     * Hapus jenis pakan.
     */
    public function destroy(FeedType $feedType)
    {
        // Security Check
        $this->authorizeAccess($feedType);

        // Opsional: Cek relasi feeding_records sebelum hapus
        // if ($feedType->feedingRecords()->exists()) { ... return error ... }

        $feedType->delete();

        return redirect()->route('feed-types.index')->with('success', 'Jenis pakan berhasil dihapus.');
    }

    /**
     * Helper Function: Proteksi Akses
     */
    private function authorizeAccess(FeedType $feedType)
    {
        $user = Auth::user();

        // Jika user adalah Mitra DAN data bukan miliknya -> 403 Forbidden
        if ($user->role === 'mitra' && $feedType->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }
    }
}
