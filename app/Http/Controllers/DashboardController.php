<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sheep;
use App\Models\HealthRecord;
use App\Models\ReproductionRecord;
use App\Models\ProfitLossRecord;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // --- LOGIKA FILTER BERDASARKAN ROLE ---
        $isPartner = $user->role === 'mitra';
        $partnerId = $isPartner ? $user->id : null;

        // Base Query untuk Domba
        $sheepQuery = Sheep::query();
        $healthQuery = HealthRecord::query();
        $reproQuery = ReproductionRecord::query();

        // Filter: Jika Mitra, scope semua data ke domba yang dititipkan kepadanya
        if ($isPartner) {
            $sheepQuery->where('partner_id', $partnerId);
            $scopedSheepIds = $sheepQuery->pluck('id');

            $healthQuery->whereIn('sheep_id', $scopedSheepIds);
            $reproQuery->whereIn('female_sheep_id', $scopedSheepIds)
                       ->orWhereIn('male_sheep_id', $scopedSheepIds);
        }

        // 1. STATISTIK UTAMA
        $totalSheep = $sheepQuery->count();
        $activeSicknessCount = $healthQuery->whereNotIn('status', ['Completed', 'Sembuh / Selesai'])->count();
        $pregnantCount = $reproQuery->where('status', 'Pregnant')->count();

        // KEUANGAN BULAN INI
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Filter user_id untuk keuangan (agar mitra hanya lihat uangnya sendiri)
        $financeQuery = ProfitLossRecord::query();
        if ($isPartner) {
            $financeQuery->where('user_id', $user->id);
        }

        $incomeThisMonth = (clone $financeQuery)->whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->where('type', 'income')
            ->sum('amount');

        $expenseThisMonth = (clone $financeQuery)->whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->where('type', 'expense')
            ->sum('amount');

        $balanceThisMonth = $incomeThisMonth - $expenseThisMonth;

        // 2. DATA UNTUK GRAFIK - INISIALISASI ARRAY DENGAN 0
        $monthlyIncome = array_fill(1, 12, 0);
        $monthlyExpense = array_fill(1, 12, 0);
        $monthlySheep = array_fill(1, 12, 0);

        // QUERY DATA KEUANGAN PER BULAN (TREN)
        $financials = (clone $financeQuery)->select(
                DB::raw('MONTH(date) as month'),
                'type',
                DB::raw('SUM(amount) as total')
            )
            ->whereYear('date', $currentYear)
            ->groupBy('month', 'type')
            ->get();

        foreach ($financials as $record) {
            if ($record->type === 'income') {
                $monthlyIncome[$record->month] = (float) $record->total;
            } else if ($record->type === 'expense') {
                $monthlyExpense[$record->month] = (float) $record->total;
            }
        }

        // --- TAMBAHAN BARU: DATA KATEGORI PENGELUARAN (PIE CHART) ---
        $expenseCategories = (clone $financeQuery)
            ->select('category', DB::raw('SUM(amount) as total'))
            ->where('type', 'expense')
            ->whereYear('date', $currentYear)
            ->groupBy('category')
            ->orderByDesc('total')
            ->limit(5) // Ambil 5 kategori terbesar
            ->get();


        $expenseLabels = $expenseCategories->pluck('category');
        $expenseTotals = $expenseCategories->pluck('total');

        // QUERY DATA PERTUMBUHAN DOMBA PER BULAN
        $sheepGrowth = Sheep::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as total')
            )
            ->whereYear('created_at', $currentYear)
            ->when($isPartner, fn($q) => $q->where('partner_id', $partnerId))
            ->groupBy('month')
            ->get();

        foreach ($sheepGrowth as $record) {
            $monthlySheep[$record->month] = $record->total;
        }

        // 3. LIST DATA (Sakit & Hamil)
        $scopedSheeps = $sheepQuery->with('healthRecords')->get();

        $sickSheepList = $scopedSheeps->filter(function ($sheep) {
            $latestRecord = $sheep->healthRecords
                ->sortByDesc(fn($record) => $record->record_date . str_pad($record->id, 10, '0', STR_PAD_LEFT))
                ->first();
            return $latestRecord && !in_array($latestRecord->status, ['Completed', 'Sembuh / Selesai']);
        })->map(fn($sheep) => $sheep->healthRecords
                ->sortByDesc(fn($record) => $record->record_date . str_pad($record->id, 10, '0', STR_PAD_LEFT))
                ->first()
        )
        ->sortByDesc('record_date')
        ->take(5);

        // Daftar Domba Hamil
        $pregnantSheepList = ReproductionRecord::with(['dam', 'dam.shelter'])
            ->where('status', 'Pregnant')
            ->when($isPartner, fn($q) => $q->whereIn('female_sheep_id',
                $scopedSheeps->where('gender', 'Betina')->pluck('id')
            ))
            ->orderBy('expected_delivery_date', 'asc')
            ->limit(5)
            ->get();

        $recentTransactions = (clone $financeQuery)->latest('date')->limit(5)->get();
        $dashboardTitle = $isPartner ? 'Dashboard Mitra' : 'Dashboard Peternakan';

        return view('dashboard', compact(
            'totalSheep', 'activeSicknessCount', 'pregnantCount', 'balanceThisMonth',
            'sickSheepList', 'pregnantSheepList', 'recentTransactions',
            'monthlyIncome', 'monthlyExpense', 'monthlySheep',
            'dashboardTitle',
            'expenseLabels', 'expenseTotals' // <-- Variable Baru
        ));
    }
}
