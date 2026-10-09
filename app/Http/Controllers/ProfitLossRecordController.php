<?php

namespace App\Http\Controllers;

use App\Models\ProfitLossRecord;
use App\Models\Sheep;
use App\Models\Shelter;
use App\Models\User; // Tambahkan Model User
use App\Http\Requests\StoreProfitLossRecordRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ProfitLossRecordController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Mulai query dasar dengan eager loading
        $query = ProfitLossRecord::with(['sheep', 'shelter', 'user']);

        // --- LOGIKA ISOLASI & FILTER DOMPET ---
        if ($user->role === 'mitra') {
            // SKENARIO 1: MITRA
            // Mutlak hanya melihat keuangan mereka sendiri
            $query->where('user_id', $user->id);
        } else {
            // SKENARIO 2: ADMIN
            if ($request->filled('partner_id')) {
                // Jika "Semua Data", jangan filter user_id (Lihat Cashflow Gabungan)
                if ($request->partner_id === 'all') {
                    // No filter
                } else {
                    // Filter Mitra Tertentu (Mode Audit)
                    $query->where('user_id', $request->partner_id);
                }
            } else {
                // DEFAULT (Tanpa Filter): Hanya lihat Kas Pusat (Admin)
                $query->where('user_id', $user->id);
            }
        }

        // --- FILTER STANDAR LAINNYA ---

        if ($request->filled('start_date')) {
            $query->whereDate('date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('date', '<=', $request->end_date);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // --- AMBIL DATA PAGINASI ---
        $records = $query->orderByDesc('date')
                         ->orderByDesc('id')
                         ->paginate(10)
                         ->withQueryString();

        // --- HITUNG RINGKASAN (SALDO DINAMIS) ---
        // Clone query agar perhitungan saldo mengikuti filter yang aktif
        $summaryQuery = clone $query;
        // Hapus limit/offset pagination agar menghitung total keseluruhan data yang cocok
        $summaryQuery->getQuery()->orders = []; // Reset order agar query lebih cepat
        $summaryQuery->getQuery()->limit = null;
        $summaryQuery->getQuery()->offset = null;

        // Optimasi: Gunakan agregat database langsung daripada get() -> sum() di PHP
        // Ini jauh lebih cepat jika datanya ribuan
        $totalIncome = $summaryQuery->clone()->where('type', 'income')->sum('amount');
        $totalExpense = $summaryQuery->clone()->where('type', 'expense')->sum('amount');
        $balance = $totalIncome - $totalExpense;

        // --- DATA PENDUKUNG VIEW ---
        // Ambil daftar mitra untuk dropdown filter (Khusus Admin)
        $partners = [];
        if ($user->role !== 'mitra') {
            $partners = User::where('role', 'mitra')->orderBy('name')->get(['id', 'name']);
        }

        return view('profit_loss.index', compact('records', 'totalIncome', 'totalExpense', 'balance', 'partners'));
    }

    public function create()
    {
        $user = Auth::user();

        // Filter Dropdown Domba (Hanya tampilkan domba milik user)
        $sheepsQuery = Sheep::orderBy('tag_number');
        $sheltersQuery = Shelter::orderBy('name');

        if ($user->role === 'mitra') {
            $sheepsQuery->where('partner_id', $user->id); // Asumsi mitra terikat di partner_id domba
            $sheltersQuery->where('user_id', $user->id);
        }

        $sheeps = $sheepsQuery->get(['id', 'tag_number']);
        $shelters = $sheltersQuery->get(['id', 'name']);

        return view('profit_loss.create', compact('sheeps', 'shelters'));
    }

    public function store(StoreProfitLossRecordRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id(); // Otomatis masuk ke "Dompet" penginput

        ProfitLossRecord::create($data);

        return redirect()->route('profit-loss.index')->with('success', 'Transaksi berhasil dicatat!');
    }

    public function destroy(ProfitLossRecord $profitLoss)
    {
        // Security Check: Mitra tidak boleh hapus data orang lain
        $this->authorizeAccess($profitLoss);

        $profitLoss->delete();
        return redirect()->route('profit-loss.index')->with('success', 'Transaksi berhasil dihapus.');
    }

    /**
     * Helper: Proteksi Akses
     */
    private function authorizeAccess(ProfitLossRecord $record)
    {
        $user = Auth::user();
        if ($user->role === 'mitra' && $record->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses ke data transaksi ini.');
        }
    }
}
