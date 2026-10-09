<?php

namespace App\Http\Controllers;

use App\Models\QrDestination;
use App\Models\QrClient;
use App\Models\QrInvoice;
use App\Models\QrScanEvent;
use App\Models\QrTemplate;
use App\Models\QrUnit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class QrAdminPageController extends Controller
{
    public function dashboard(): View
    {
        $stats = [
            'templates' => QrTemplate::count(),
            'units' => QrUnit::count(),
            'empty' => QrUnit::where('status', QrUnit::STATUS_EMPTY)->count(),
            'pending' => QrUnit::where('status', QrUnit::STATUS_PENDING)->count(),
            'active' => QrUnit::where('status', QrUnit::STATUS_ACTIVE)->count(),
            'disabled' => QrUnit::where('status', QrUnit::STATUS_DISABLED)->count(),
            'clients' => QrClient::count(),
            'active_subscriptions' => \App\Models\QrSubscription::where('status', 'ACTIVE')->where('ends_at', '>', now())->count(),
            'pending_invoices' => QrInvoice::where('status', 'PENDING')->count(),
        ];
        $recentUnits = QrUnit::with(['template', 'currentDestination'])->latest()->limit(8)->get();
        $scanTotal = QrScanEvent::where('event_type', QrScanEvent::TYPE_SCAN)->count();
        $reviewClickTotal = QrScanEvent::where('event_type', QrScanEvent::TYPE_REVIEW_CLICK)->count();
        $dailyActivity = $this->dailyActivity();

        return view('qr.admin.dashboard', compact('stats', 'recentUnits', 'scanTotal', 'reviewClickTotal', 'dailyActivity'));
    }

    public function analytics(): View
    {
        $templates = QrTemplate::query()
            ->withCount([
                'units as total_units',
                'units as empty_units' => fn ($query) => $query->where('status', QrUnit::STATUS_EMPTY),
                'units as pending_units' => fn ($query) => $query->where('status', QrUnit::STATUS_PENDING),
                'units as active_units' => fn ($query) => $query->where('status', QrUnit::STATUS_ACTIVE),
                'units as disabled_units' => fn ($query) => $query->where('status', QrUnit::STATUS_DISABLED),
            ])
            ->orderBy('name')
            ->get();
        $destinationCount = QrDestination::whereNull('deactivated_at')->count();
        $paidRevenue = QrInvoice::where('status', 'PAID')->sum('amount');
        $pendingInvoices = QrInvoice::where('status', 'PENDING')->count();
        $eventCounts = QrScanEvent::query()
            ->join('qr_units', 'qr_units.id', '=', 'qr_scan_events.qr_unit_id')
            ->select('qr_units.qr_template_id', 'qr_scan_events.event_type', DB::raw('COUNT(*) as total'))
            ->groupBy('qr_units.qr_template_id', 'qr_scan_events.event_type')
            ->get()
            ->groupBy('qr_template_id');
        foreach ($templates as $template) {
            $counts = $eventCounts->get($template->id, collect())->keyBy('event_type');
            $template->scan_events = (int) ($counts->get(QrScanEvent::TYPE_SCAN)?->total ?? 0);
            $template->review_click_events = (int) ($counts->get(QrScanEvent::TYPE_REVIEW_CLICK)?->total ?? 0);
        }
        $scanTotal = QrScanEvent::where('event_type', QrScanEvent::TYPE_SCAN)->count();
        $reviewClickTotal = QrScanEvent::where('event_type', QrScanEvent::TYPE_REVIEW_CLICK)->count();
        $dailyActivity = $this->dailyActivity();

        return view('qr.admin.analytics', compact('templates', 'destinationCount', 'paidRevenue', 'pendingInvoices', 'scanTotal', 'reviewClickTotal', 'dailyActivity'));
    }

    public function help(): View
    {
        return view('qr.admin.help');
    }

    private function dailyActivity(): array
    {
        $start = Carbon::today()->subDays(13)->startOfDay();
        $rows = QrScanEvent::query()
            ->where('created_at', '>=', $start)
            ->selectRaw('DATE(created_at) as day, event_type, COUNT(*) as total')
            ->groupBy('day', 'event_type')
            ->get();
        $counts = [];
        foreach ($rows as $row) {
            $counts[$row->day][$row->event_type] = (int) $row->total;
        }

        $days = [];
        for ($offset = 13; $offset >= 0; $offset--) {
            $date = Carbon::today()->subDays($offset);
            $key = $date->toDateString();
            $days[] = [
                'label' => $date->format('d/m'),
                'scans' => $counts[$key][QrScanEvent::TYPE_SCAN] ?? 0,
                'reviews' => $counts[$key][QrScanEvent::TYPE_REVIEW_CLICK] ?? 0,
            ];
        }

        return $days;
    }
}
