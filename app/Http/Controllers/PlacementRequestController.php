<?php

namespace App\Http\Controllers;

use App\Models\PlacementRequest;
use App\Models\Shelter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlacementRequestController extends Controller
{
    /**
     * Menampilkan daftar request masuk (Untuk Dashboard Mitra).
     */
    public function index()
    {
        $user = Auth::user();

        // Hanya Mitra yang melihat request masuk
        if ($user->role !== 'mitra') {
            abort(403, 'Hanya mitra yang dapat mengakses halaman ini.');
        }

        // Ambil request yang statusnya pending dan ditujukan ke user ini
        $requests = PlacementRequest::with(['sheep', 'requester'])
            ->where('target_partner_id', $user->id)
            ->where('status', 'pending')
            ->latest()
            ->paginate(10);

        // Ambil daftar kandang milik mitra untuk dropdown di modal terima
        $myShelters = Shelter::where('user_id', $user->id)
            ->orderBy('name')
            ->get();

        return view('placement_requests.index', compact('requests', 'myShelters'));
    }

    /**
     * Mitra Menerima Request (ACC).
     */
    public function approve(Request $request, $id)
    {
        $placementRequest = PlacementRequest::findOrFail($id);

        // Security: Pastikan yang approve adalah mitra yang dituju
        if (Auth::id() !== $placementRequest->target_partner_id) {
            abort(403, 'Unauthorized action.');
        }

        // Validasi input kandang
        $request->validate([
            'shelter_id' => 'required|exists:shelters,id'
        ]);

        // 1. Update status Request jadi approved
        $placementRequest->update(['status' => 'approved']);

        // 2. Update Data Domba (Resmi pindah tangan ke mitra & masuk kandang mitra)
        $sheep = $placementRequest->sheep;
        $sheep->update([
            'partner_id' => Auth::id(),        // ID Mitra yang login
            'shelter_id' => $request->shelter_id, // Kandang pilihan mitra
            'placement_status' => 'Partner'    // Status penempatan (bisa disesuaikan logic bisnis Anda)
        ]);

        return redirect()->back()->with('success', 'Domba berhasil diterima dan ditempatkan di kandang Anda.');
    }

    /**
     * Mitra Menolak Request.
     */
    public function reject(Request $request, $id)
    {
        $placementRequest = PlacementRequest::findOrFail($id);

        // Security Check
        if (Auth::id() !== $placementRequest->target_partner_id) {
            abort(403, 'Unauthorized action.');
        }

        // Update status request jadi rejected dengan catatan
        $placementRequest->update([
            'status' => 'rejected',
            'notes' => $request->input('reason', 'Ditolak oleh mitra.')
        ]);

        // Domba tetap di status/lokasi sebelumnya (milik Admin/Internal)

        return redirect()->back()->with('info', 'Permintaan penempatan domba telah ditolak.');
    }
}
