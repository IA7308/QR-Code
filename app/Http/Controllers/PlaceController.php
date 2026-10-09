<?php

namespace App\Http\Controllers;

use App\Models\Place;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\View\View;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PlaceController extends Controller
{
    public function index(Request $request): View
    {
        $query = Place::query()->with('user')->latest();

        if (Auth::user()->role !== 'admin') {
            $query->where('user_id', Auth::id());
        }

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $query->where(function ($builder) use ($search) {
                $builder->where('name', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%");
            });
        }

        $places = $query->paginate(10)->withQueryString();

        return view('places.index', compact('places'));
    }

    public function create(): View
    {
        return view('places.create');
    }

    public function createQrDraft(): RedirectResponse
    {
        abort_unless(Auth::user()->role === 'admin', 403);

        $place = Place::create([
            'user_id' => Auth::id(),
            'name' => '',
            'address' => '',
            'qr_sale_status' => 'pending_payment',
        ]);

        return redirect()->route('places.show', $place)
            ->with('success', 'Template QR dibuat. Lengkapi data klien dan tempat, lalu konfirmasi pembayaran untuk mengaktifkan unduhan.');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $data['user_id'] = Auth::id();
        $place = Place::create($data);

        return redirect()->route('places.show', $place)->with('success', 'Tempat berhasil didaftarkan.');
    }

    public function show(Place $place): View
    {
        $this->authorizePlace($place);

        return view('places.show', compact('place'));
    }

    public function publicShow(Place $place): View|RedirectResponse
    {
        if ($place->qr_sale_status === 'pending_payment') {
            return view('places.public', compact('place'));
        }

        if (filled($place->google_review_url)) {
            return redirect()->away($place->google_review_url);
        }

        return view('places.public', compact('place'));
    }

    public function guestReview(Request $request, Place $place): RedirectResponse
    {
        if ($place->qr_sale_status === 'pending_payment') {
            abort(403);
        }

        $data = $request->validate([
            'place_name' => ['required', 'string', 'max:255'],
            'place_address' => ['required', 'string', 'max:2048'],
        ]);

        $addressInput = trim($data['place_address']);
        $mapsPlaceName = $this->mapsPlaceNameFromUrl($addressInput);
        if (filter_var($addressInput, FILTER_VALIDATE_URL) && $mapsPlaceName === null) {
            return redirect()->route('places.public', $place)
                ->withInput()
                ->withErrors(['place_address' => 'Gunakan alamat biasa atau URL Google Maps lengkap yang memuat nama tempat.']);
        }

        $apiKey = config('services.google_maps.api_key');
        if (filled($place->google_review_url)) {
            return redirect()->away($place->google_review_url);
        }

        if (filled($place->name) || filled($place->address)) {
            return redirect()->route('places.public', $place)
                ->withInput()
                ->withErrors(['place_address' => 'QR ini sudah memiliki data tempat. Silakan pindai ulang QR.']);
        }

        if (!filled($apiKey)) {
            return redirect()->route('places.public', $place)
                ->withInput()
                ->withErrors(['place_address' => 'Pencarian Google Maps belum aktif. API key belum dikonfigurasi.']);
        }

        try {
            $response = Http::timeout(12)
                ->withHeaders([
                    'X-Goog-Api-Key' => $apiKey,
                    'X-Goog-FieldMask' => 'places.id,places.googleMapsUri,places.formattedAddress',
                ])
                ->post('https://places.googleapis.com/v1/places:searchText', [
                    'textQuery' => trim($data['place_name'] . ' ' . ($mapsPlaceName ?? $addressInput)),
                    'languageCode' => 'id',
                    'regionCode' => 'ID',
                    'maxResultCount' => 1,
                ]);
        } catch (\Throwable $exception) {
            report($exception);

            return redirect()->route('places.public', $place)
                ->withInput()
                ->withErrors(['place_address' => 'Google Maps tidak dapat dihubungi. Coba lagi beberapa saat.']);
        }

        if (!$response->successful()) {
            report('Google Places API request failed: HTTP ' . $response->status());

            return redirect()->route('places.public', $place)
                ->withInput()
                ->withErrors(['place_address' => 'Pencarian Google Maps gagal. Periksa konfigurasi Places API dan API key.']);
        }

        $googlePlace = $response->json('places.0');
        if (!is_array($googlePlace) || !filled($googlePlace['id'] ?? null)) {
            return redirect()->route('places.public', $place)
                ->withInput()
                ->withErrors(['place_address' => 'Tempat tidak ditemukan di Google Maps. Periksa nama dan alamat.']);
        }

        $mapsUrl = $googlePlace['googleMapsUri']
            ?? 'https://www.google.com/maps/search/?' . http_build_query([
                'api' => '1',
                'query' => trim($data['place_name'] . ' ' . ($mapsPlaceName ?? $addressInput)),
                'query_place_id' => $googlePlace['id'],
            ]);
        $savedAddress = $googlePlace['formattedAddress'] ?? ($mapsPlaceName ?? $addressInput);
        $reviewUrl = 'https://search.google.com/local/writereview?' . http_build_query([
            'placeid' => $googlePlace['id'],
        ]);

        $saved = Place::query()
            ->whereKey($place->getKey())
            ->where('name', '')
            ->where('address', '')
            ->whereNull('google_review_url')
            ->update([
                'name' => $data['place_name'],
                'address' => mb_substr($savedAddress, 0, 255),
                'google_maps_url' => $mapsUrl,
                'google_review_url' => $reviewUrl,
                'updated_at' => now(),
            ]);

        if ($saved !== 1) {
            $place->refresh();

            if (filled($place->google_review_url)) {
                return redirect()->away($place->google_review_url);
            }

            return redirect()->route('places.public', $place)
                ->withErrors(['place_address' => 'QR ini baru saja diperbarui. Pindai ulang untuk melanjutkan.']);
        }

        return redirect()->away($reviewUrl);
    }

    public function edit(Place $place): View
    {
        $this->authorizePlace($place);

        return view('places.edit', compact('place'));
    }

    public function update(Request $request, Place $place): RedirectResponse
    {
        $this->authorizePlace($place);

        if ($place->qr_sale_status === 'pending_payment') {
            abort_unless(Auth::user()->role === 'admin', 403);

            $data = $request->validate([
                'buyer_name' => ['required', 'string', 'max:255'],
                'buyer_phone' => ['required', 'string', 'max:50'],
                'sale_price' => ['required', 'integer', 'min:1'],
                'name' => ['required', 'string', 'max:255'],
                'address_or_maps_link' => ['required', 'string', 'max:2048'],
            ]);

            try {
                $googlePlace = $this->findGooglePlace($data['name'], $data['address_or_maps_link']);
            } catch (\Throwable $exception) {
                report($exception);

                return redirect()->route('places.edit', $place)
                    ->withInput()
                    ->withErrors(['address_or_maps_link' => $exception->getMessage()]);
            }

            $reviewUrl = 'https://search.google.com/local/writereview?' . http_build_query([
                'placeid' => $googlePlace['id'],
            ]);

            $place->update([
                'buyer_name' => $data['buyer_name'],
                'buyer_phone' => $data['buyer_phone'],
                'sale_price' => $data['sale_price'],
                'name' => $data['name'],
                'address' => mb_substr($googlePlace['formattedAddress'] ?? $googlePlace['address_fallback'], 0, 255),
                'google_maps_url' => $googlePlace['googleMapsUri'] ?? null,
                'google_review_url' => $reviewUrl,
            ]);

            return redirect()->route('places.show', $place)
                ->with('success', 'Data klien dan tujuan Google Review tersimpan. Konfirmasi pembayaran untuk membuka unduhan QR.');
        }

        $place->update($this->validatedData($request));

        return redirect()->route('places.show', $place)->with('success', 'Data tempat berhasil diperbarui.');
    }

    public function destroy(Place $place): RedirectResponse
    {
        $this->authorizePlace($place);
        $place->delete();

        return redirect()->route('places.index')->with('success', 'Tempat berhasil dihapus.');
    }

    public function confirmTemplatePayment(Place $place): RedirectResponse
    {
        abort_unless(Auth::user()->role === 'admin', 403);
        abort_unless($place->qr_sale_status === 'pending_payment', 409, 'Status pembayaran template ini sudah berubah.');
        abort_unless(
            filled($place->buyer_name) && filled($place->buyer_phone) && $place->sale_price > 0 && filled($place->google_review_url),
            422,
            'Lengkapi data klien, harga, dan Google Review sebelum mengonfirmasi pembayaran.'
        );

        $place->update([
            'qr_sale_status' => 'paid',
            'paid_at' => now(),
        ]);

        return redirect()->route('places.show', $place)
            ->with('success', 'Pembayaran dikonfirmasi. QR sekarang aktif dan siap diunduh untuk diberikan kepada klien.');
    }

    public function downloadQr(Place $place)
    {
        $this->authorizePlace($place);
        abort_unless($place->qr_sale_status !== 'pending_payment', 403, 'QR template dapat diunduh setelah pembayaran dikonfirmasi.');

        $svg = QrCode::format('svg')->size(600)->margin(2)->generate(route('places.public', $place));
        $filename = Str::slug($place->name ?: 'tempat-' . $place->id) . '-qr.svg';

        return response($svg, 200, [
            'Content-Type' => 'image/svg+xml',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    private function validatedData(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'google_review_url' => ['nullable', 'url', 'max:2048'],
        ]);

        $search = trim($data['name'] . ' ' . $data['address']);
        $data['google_maps_url'] = 'https://www.google.com/maps/search/?' . http_build_query([
            'api' => '1',
            'query' => $search,
        ]);
        $data['google_review_url'] = filled($data['google_review_url'] ?? null)
            ? $data['google_review_url']
            : null;

        return $data;
    }

    private function authorizePlace(Place $place): void
    {
        abort_unless(Auth::user()->role === 'admin' || $place->user_id === Auth::id(), 403);
    }

    private function mapsPlaceNameFromUrl(string $value): ?string
    {
        if (!filter_var($value, FILTER_VALIDATE_URL)) {
            return null;
        }

        $host = strtolower(parse_url($value, PHP_URL_HOST) ?? '');
        if (!in_array($host, ['google.com', 'www.google.com', 'maps.google.com'], true)) {
            return null;
        }

        $path = parse_url($value, PHP_URL_PATH) ?? '';
        if (!preg_match('~/maps/place/([^/]+)~', $path, $matches)) {
            return null;
        }

        return trim(urldecode($matches[1]));
    }

    private function findGooglePlace(string $name, string $addressOrMapLink): array
    {
        $input = trim($addressOrMapLink);
        $mapsPlaceName = $this->mapsPlaceNameFromUrl($input);
        if (filter_var($input, FILTER_VALIDATE_URL) && $mapsPlaceName === null) {
            throw new \RuntimeException('Masukkan alamat biasa atau link Google Maps lengkap yang memuat nama tempat.');
        }

        $apiKey = config('services.google_maps.api_key');
        if (!filled($apiKey)) {
            throw new \RuntimeException('Google Places API belum dikonfigurasi.');
        }

        try {
            $response = Http::timeout(12)
                ->withHeaders([
                    'X-Goog-Api-Key' => $apiKey,
                    'X-Goog-FieldMask' => 'places.id,places.googleMapsUri,places.formattedAddress',
                ])
                ->post('https://places.googleapis.com/v1/places:searchText', [
                    'textQuery' => trim($name . ' ' . ($mapsPlaceName ?? $input)),
                    'languageCode' => 'id',
                    'regionCode' => 'ID',
                    'maxResultCount' => 1,
                ]);
        } catch (\Throwable $exception) {
            throw new \RuntimeException('Google Maps tidak dapat dihubungi. Coba lagi beberapa saat.', previous: $exception);
        }

        if (!$response->successful()) {
            report('Google Places API request failed: HTTP ' . $response->status());
            throw new \RuntimeException('Pencarian Google Maps gagal. Periksa Places API, billing, dan pembatasan API key.');
        }

        $googlePlace = $response->json('places.0');
        if (!is_array($googlePlace) || !filled($googlePlace['id'] ?? null)) {
            throw new \RuntimeException('Tempat tidak ditemukan. Periksa nama dan alamat atau link Google Maps.');
        }

        $googlePlace['address_fallback'] = $mapsPlaceName ?? $input;

        return $googlePlace;
    }
}
