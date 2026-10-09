<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Sheep;
use App\Models\Shelter;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\PlacementRequest;
use App\Models\ProfitLossRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreSheepRequest;
use App\Http\Requests\UpdateSheepRequest;
use App\Services\PriceRecommenderService;

class SheepController extends Controller
{
    /**
     * Menampilkan daftar semua data domba dengan fitur Search & Filter.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $isPartner = $user->role === 'mitra'; // Cek role

        // UPDATE: Tambahkan 'latestPlacementRequest' agar kita bisa cek status di View
        $query = Sheep::with(['shelter', 'partner', 'latestPlacementRequest']);

        // FILTER SCOPE MITRA
        if ($isPartner) {
            $query->where('partner_id', $user->id);
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('tag_number', 'like', "%{$search}%");
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->input('gender'));
        }

        if ($request->filled('shelter_id')) {
            $query->where('shelter_id', $request->input('shelter_id'));
        }

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        $sheep = $query->latest()
                        ->paginate(12)
                        ->withQueryString();

        // Filter Kandang di Index juga (untuk dropdown filter)
        $sheltersQuery = Shelter::orderBy('name');
        if ($isPartner) {
            $sheltersQuery->where('user_id', $user->id);
        }
        $shelters = $sheltersQuery->get(['id', 'name']);

        $categories = Sheep::select('category')->distinct()->pluck('category');

        return view('sheep.index', compact('sheep', 'shelters', 'categories', 'isPartner'));
    }

    /**
     * Menampilkan form untuk membuat domba baru.
     */
    public function create()
    {
        $user = Auth::user();
        $isPartner = $user->role === 'mitra';

        // --- UPDATE: FILTER KANDANG ---
        $sheltersQuery = Shelter::orderBy('name');
        if ($isPartner) {
            $sheltersQuery->where('user_id', $user->id);
        }
        $shelters = $sheltersQuery->get();

        // FILTER PARTNER
        $partnersQuery = User::orderBy('name');
        if ($isPartner) {
            $partnersQuery->where('id', $user->id);
        }
        $partners = $partnersQuery->get(['id', 'name']);

        // FILTER INDUKAN
        $damsQuery = Sheep::where('gender', 'Betina');
        $siresQuery = Sheep::where('gender', 'Jantan');

        if ($isPartner) {
            $damsQuery->where('partner_id', $user->id);
            $siresQuery->where('partner_id', $user->id);
        }

        $potential_dams = $damsQuery->get(['id', 'tag_number']);
        $potential_sires = $siresQuery->get(['id', 'tag_number']);

        return view('sheep.create', compact('shelters', 'partners', 'potential_dams', 'potential_sires', 'isPartner'));
    }

    /**
     * Menyimpan data domba baru ke database.
     */
    public function store(StoreSheepRequest $request)
    {
        $user = Auth::user();
        $data = $request->validated();
        $targetPartnerId = null;

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('sheep_photos', 'public');
            $data['photo_path'] = $path;
        }

        $data['user_id'] = $user->id;

        // --- LOGIKA REQUEST PENEMPATAN ---
        if ($user->role === 'mitra') {
            // Mitra input sendiri -> Langsung jadi miliknya
            $data['partner_id'] = $user->id;
            $data['placement_status'] = 'Internal'; // Mitra mengelola sendiri = Internal bagi Mitra
        } else {
            // ADMIN INPUT
            $placementStatus = $request->input('placement_status', 'Internal');

            if ($placementStatus === 'Partner') {
                $targetPartnerId = $request->input('partner_id');
                $data['partner_id'] = null;
                $data['shelter_id'] = null;
                $data['placement_status'] = 'Partner';
            } else {
                // Internal Admin
                $data['partner_id'] = null;
            }
        }

        // 1. Buat Domba
        $sheep = Sheep::create($data);

        // 2. Jika Admin & Target Mitra ada -> Buat Request
        if ($user->role !== 'mitra' && $targetPartnerId) {
            PlacementRequest::create([
                'sheep_id' => $sheep->id,
                'requester_id' => $user->id,
                'target_partner_id' => $targetPartnerId,
                'status' => 'pending',
                'notes' => 'Penempatan baru dari Admin'
            ]);

            return redirect()->route('sheep.index')->with('success', 'Domba dibuat. Permintaan persetujuan dikirim ke Mitra.');
        }

        return redirect()->route('sheep.index')->with('success', 'Data domba baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan halaman detail satu domba.
     */
    public function show(Sheep $sheep)
    {
        $this->authorizeAccess($sheep);

        $sheep->load(['shelter', 'mother', 'father', 'partner', 'latestPlacementRequest']);

        $recommender = new PriceRecommenderService();
        $priceData = $recommender->calculate($sheep);

        $weightRecords = $sheep->weightRecords()
                               ->orderByDesc('weighing_date')
                               ->orderByDesc('id')
                               ->paginate(5, ['*'], 'weight_page');

        $healthRecords = $sheep->healthRecords()
                               ->with('symptoms')
                               ->orderByDesc('record_date')
                               ->orderByDesc('id')
                               ->paginate(5, ['*'], 'health_page');

        return view('sheep.show', compact('sheep', 'weightRecords', 'healthRecords', 'priceData'));
    }

    /**
     * Menampilkan form edit.
     */
    public function edit(Sheep $sheep)
    {
        $this->authorizeAccess($sheep);

        $user = Auth::user();
        $isPartner = $user->role === 'mitra';

        $sheltersQuery = Shelter::orderBy('name');
        if ($isPartner) {
            $sheltersQuery->where('user_id', $user->id);
        }
        $shelters = $sheltersQuery->get();

        $partnersQuery = User::orderBy('name');
        if ($isPartner) {
            $partnersQuery->where('id', $user->id);
        }
        $partners = $partnersQuery->get(['id', 'name']);

        $damsQuery = Sheep::where('gender', 'Betina')->where('id', '!=', $sheep->id);
        $siresQuery = Sheep::where('gender', 'Jantan')->where('id', '!=', $sheep->id);

        if ($isPartner) {
            $damsQuery->where('partner_id', $user->id);
            $siresQuery->where('partner_id', $user->id);
        }

        $potential_dams = $damsQuery->get(['id', 'tag_number']);
        $potential_sires = $siresQuery->get(['id', 'tag_number']);

        $tagSuffix = str_replace('JAS-', '', $sheep->tag_number);

        return view('sheep.edit', compact('sheep', 'shelters', 'partners', 'potential_dams', 'potential_sires', 'tagSuffix', 'isPartner'));
    }

    /**
     * Memproses update data.
     */
    public function update(UpdateSheepRequest $request, Sheep $sheep)
    {
        $this->authorizeAccess($sheep);
        $user = Auth::user();
        $data = $request->validated();

        if ($request->hasFile('photo')) {
            if ($sheep->photo_path) { Storage::disk('public')->delete($sheep->photo_path); }
            $path = $request->file('photo')->store('sheep_photos', 'public');
            $data['photo_path'] = $path;
        }

        if ($user->role === 'mitra') {
            unset($data['partner_id']);
        } else {
            $placementStatus = $request->input('placement_status', 'Internal');

            if ($placementStatus === 'Partner') {
                $newPartnerId = $request->input('partner_id');
                if ($newPartnerId && $newPartnerId != $sheep->partner_id) {
                    PlacementRequest::create([
                        'sheep_id' => $sheep->id,
                        'requester_id' => $user->id,
                        'target_partner_id' => $newPartnerId,
                        'status' => 'pending',
                        'notes' => 'Pemindahan domba dari Admin'
                    ]);
                    $data['partner_id'] = null;
                    $data['shelter_id'] = null;
                }
            } else {
                $data['partner_id'] = null;
            }
        }

        $sheep->update($data);

        return redirect()->route('sheep.show', $sheep)->with('success', 'Data domba berhasil diperbarui.');
    }

    public function printCard(Sheep $sheep)
    {
        $this->authorizeAccess($sheep);
        $sheep->load(['shelter', 'mother', 'father', 'healthRecords', 'weightRecords']);
        $data = ['sheep' => $sheep, 'farm_name' => 'JAS Farm Sinergi', 'print_date' => now()->format('d F Y')];
        $pdf = Pdf::loadView('sheep.print_card', $data);
        $pdf->setPaper('a4', 'portrait');
        return $pdf->stream('Kartu_Ternak_' . $sheep->tag_number . '.pdf');
    }

    public function destroy(Sheep $sheep)
    {
        $this->authorizeAccess($sheep);
        if ($sheep->photo_path) { Storage::disk('public')->delete($sheep->photo_path); }
        $sheep->delete();
        return redirect()->route('sheep.index')->with('success', 'Data domba berhasil dihapus.');
    }

    private function authorizeAccess(Sheep $sheep)
    {
        $user = Auth::user();
        if ($user->role === 'mitra' && $sheep->partner_id !== $user->id) {
            abort(403, 'Anda tidak memiliki izin untuk mengakses data domba ini.');
        }
    }
}
