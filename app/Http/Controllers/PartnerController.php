<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Sheep;
use App\Models\Shelter;
use App\Models\HealthRecord;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    /**
     * Menampilkan daftar semua Mitra dengan fitur pencarian.
     */
    public function index(Request $request)
    {
        // Base Query: User dengan role 'mitra'
        $query = User::where('role', 'mitra')
            ->withCount(['sheeps as total_sheep']); // Hitung total domba per mitra

        // 1. Logika Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        // 2. Sorting & Pagination
        $partners = $query->orderBy('name')->paginate(9)->withQueryString();

        // 3. Statistik Global (Untuk Header Halaman)
        $totalPartners = User::where('role', 'mitra')->count();
        $totalPlasmaSheep = Sheep::whereNotNull('partner_id')->count(); // Total domba yang ada di mitra

        return view('partners.index', compact('partners', 'totalPartners', 'totalPlasmaSheep'));
    }

    /**
     * Menampilkan detail Mitra dan daftar domba mereka.
     */
    public function show(User $partner)
    {
        if ($partner->role !== 'mitra') {
            abort(404);
        }

        // 1. AMBIL DOMBA (PAGINATION)
        $sheep = Sheep::where('partner_id', $partner->id)
                      ->with('shelter')
                      ->latest()
                      ->paginate(12);

        // 2. STATISTIK POPULASI
        $partnerSheepQuery = Sheep::where('partner_id', $partner->id);

        $stats = [
            'total'  => (clone $partnerSheepQuery)->count(),
            'male'   => (clone $partnerSheepQuery)->where('gender', 'Jantan')->count(),
            'female' => (clone $partnerSheepQuery)->where('gender', 'Betina')->count(),
        ];

        // 3. STATISTIK KESEHATAN
        $scopedSheepIds = (clone $partnerSheepQuery)->pluck('id');
        $stats['sick'] = HealthRecord::whereIn('sheep_id', $scopedSheepIds)
            ->whereNotIn('status', ['Completed', 'Sembuh / Selesai'])
            ->count();

        // 4. DATA KANDANG
        $shelters = Shelter::where('user_id', $partner->id)
            ->withCount('sheep')
            ->get();

        $stats['shelter_count']  = $shelters->count();
        $stats['total_capacity'] = $shelters->sum('capacity');
        $stats['capacity_used']  = $shelters->sum('sheep_count');

        return view('partners.show', compact('partner', 'sheep', 'stats', 'shelters'));
    }
}
