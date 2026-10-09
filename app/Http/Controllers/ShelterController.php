<?php

namespace App\Http\Controllers;

use App\Models\Shelter;
use App\Models\User; // Tambahkan Model User
use App\Http\Requests\StoreShelterRequest;
use App\Http\Requests\UpdateShelterRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ShelterController extends Controller
{
    /**
     * Menampilkan daftar Kandang dengan Search, Filter, dan Pagination.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Gunakan withCount('sheep') untuk sorting
        // Gunakan with('user') untuk efisiensi menampilkan nama pemilik di badge
        $query = Shelter::withCount('sheep')->with('user');

        // --- LOGIKA FILTER USER (Sama seperti FeedingRecord) ---
        if ($user->role === 'mitra') {
            // SKENARIO 1: MITRA
            // Mutlak hanya melihat kandang miliknya sendiri
            $query->where('user_id', $user->id);
        } else {
            // SKENARIO 2: ADMIN
            if ($request->filled('partner_id')) {
                // Cek apakah Admin memilih "Semua Data"
                if ($request->partner_id === 'all') {
                    // Jangan filter user_id apapun (Tampilkan Semua)
                } else {
                    // Filter Mitra Tertentu
                    $query->where('user_id', $request->partner_id);
                }
            } else {
                // DEFAULT (Jika tidak ada filter): Tampilkan Data Admin Saja
                $query->where('user_id', $user->id);
            }
        }

        // 1. Logika Search
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // 2. Logika Sorting
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'fullest': // Terisi Paling Banyak
                    $query->orderByDesc('sheep_count');
                    break;
                case 'emptiest': // Paling Kosong
                    $query->orderBy('sheep_count');
                    break;
                case 'capacity_high': // Kapasitas Besar
                    $query->orderByDesc('capacity');
                    break;
                case 'newest': // Terbaru Dibuat
                    $query->orderByDesc('created_at');
                    break;
                default:
                    $query->orderBy('name');
            }
        } else {
            $query->orderBy('name'); // Default urut nama A-Z
        }

        // 3. Pagination (9 per halaman agar pas di grid 3 kolom)
        $shelters = $query->paginate(9)->withQueryString();

        // Ambil daftar mitra untuk dropdown filter (Khusus Admin)
        $partners = [];
        if ($user->role !== 'mitra') {
            $partners = User::where('role', 'mitra')->orderBy('name')->get(['id', 'name']);
        }

        return view('shelters.index', compact('shelters', 'partners'));
    }

    /**
     * Menampilkan form untuk membuat Kandang baru.
     */
    public function create()
    {
        return view('shelters.create');
    }

    /**
     * Menyimpan Kandang baru ke database.
     */
    public function store(StoreShelterRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id(); // Tambahkan user ID yang input (Mitra/Admin)

        Shelter::create($data);

        return redirect()->route('shelters.index')->with('success', 'Kandang baru berhasil ditambahkan!');
    }

    /**
     * Menampilkan form edit Kandang.
     */
    public function edit(Shelter $shelter)
    {
        // SECURITY CHECK: Pastikan mitra tidak mengedit kandang orang lain
        $this->authorizeAccess($shelter);

        return view('shelters.edit', compact('shelter'));
    }

    /**
     * Memproses update data Kandang.
     */
    public function update(UpdateShelterRequest $request, Shelter $shelter)
    {
        // SECURITY CHECK
        $this->authorizeAccess($shelter);

        $shelter->update($request->validated());

        return redirect()->route('shelters.index')->with('success', 'Data Kandang berhasil diperbarui!');
    }

    /**
     * Menghapus Kandang.
     */
    public function destroy(Shelter $shelter)
    {
        // SECURITY CHECK
        $this->authorizeAccess($shelter);

        // PENTING: Cek apakah kandang memiliki domba aktif sebelum dihapus
        if ($shelter->sheep()->count() > 0) {
            return redirect()->route('shelters.index')->with('error', 'Kandang tidak dapat dihapus karena masih menampung domba!');
        }

        $shelter->delete();

        return redirect()->route('shelters.index')->with('success', 'Kandang berhasil dihapus.');
    }

    /**
     * (BARU) API untuk mendapatkan data kapasitas kandang secara realtime.
     */
    public function getCapacity(Shelter $shelter)
    {
        // SECURITY CHECK: Agar mitra lain tidak bisa intip kapasitas kandang orang lain via API
        $this->authorizeAccess($shelter);

        // Mengembalikan data JSON
        return response()->json([
            'name' => $shelter->name,
            'capacity' => $shelter->capacity,
            'current' => $shelter->current_count,
            'remaining' => $shelter->capacity - $shelter->current_count
        ]);
    }

    /**
     * Helper Function: Proteksi Akses Mitra ke Kandang
     */
    private function authorizeAccess(Shelter $shelter)
    {
        $user = Auth::user();

        // Jika user adalah Mitra DAN user_id kandang tidak sama dengan ID user
        if ($user->role === 'mitra' && $shelter->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses ke data kandang ini.');
        }
    }
}
