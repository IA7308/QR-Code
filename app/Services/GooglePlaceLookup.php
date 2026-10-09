<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class GooglePlaceLookup
{
    public function autocomplete(string $input, string $sessionToken): array
    {
        $apiKey = config('services.google_maps.api_key');
        if (!filled($apiKey)) {
            throw new RuntimeException('Google Places API belum dikonfigurasi.');
        }

        try {
            $response = Http::timeout(10)
                ->withHeaders(['X-Goog-Api-Key' => $apiKey])
                ->post('https://places.googleapis.com/v1/places:autocomplete', [
                    'input' => $input,
                    'languageCode' => 'id',
                    'regionCode' => 'ID',
                    'includeQueryPredictions' => false,
                    'sessionToken' => $sessionToken,
                ]);
        } catch (\Throwable $exception) {
            report($exception);
            throw new RuntimeException('Saran tempat tidak dapat dimuat. Periksa koneksi lalu coba lagi.', previous: $exception);
        }

        if (!$response->successful()) {
            report('Google Places Autocomplete failed with HTTP ' . $response->status());
            throw new RuntimeException('Saran Google Maps gagal dimuat. Periksa Places API dan pembatasan API key.');
        }

        return collect($response->json('suggestions', []))
            ->map(function (array $suggestion): ?array {
                $prediction = $suggestion['placePrediction'] ?? null;
                if (!is_array($prediction) || blank($prediction['placeId'] ?? null)) {
                    return null;
                }

                return [
                    'place_id' => $prediction['placeId'],
                    'name' => $prediction['structuredFormat']['mainText']['text'] ?? $prediction['text']['text'] ?? '',
                    'address' => $prediction['structuredFormat']['secondaryText']['text'] ?? $prediction['text']['text'] ?? '',
                ];
            })
            ->filter(fn (?array $place): bool => $place !== null && filled($place['name']))
            ->values()
            ->all();
    }

    public function details(string $placeId, string $sessionToken): array
    {
        $apiKey = config('services.google_maps.api_key');
        if (!filled($apiKey)) {
            throw new RuntimeException('Google Places API belum dikonfigurasi.');
        }

        try {
            $response = Http::timeout(15)
                ->withHeaders([
                    'X-Goog-Api-Key' => $apiKey,
                    'X-Goog-FieldMask' => 'id,formattedAddress,googleMapsUri',
                ])
                ->get('https://places.googleapis.com/v1/places/' . rawurlencode($placeId), [
                    'languageCode' => 'id',
                    'sessionToken' => $sessionToken,
                ]);
        } catch (\Throwable $exception) {
            report($exception);
            throw new RuntimeException('Detail tempat tidak dapat dimuat. Coba pilih tempat kembali.', previous: $exception);
        }

        if (!$response->successful() || blank($response->json('id'))) {
            report('Google Place Details failed with HTTP ' . $response->status());
            throw new RuntimeException('Tempat yang dipilih tidak dapat diverifikasi. Silakan pilih ulang dari daftar.');
        }

        $place = $response->json();

        return [
            'place_id' => $place['id'],
            'place_address' => $place['formattedAddress'] ?? '',
            'maps_url' => $place['googleMapsUri'] ?? null,
            'review_url' => 'https://search.google.com/local/writereview?' . http_build_query(['placeid' => $place['id']]),
        ];
    }

    public function nearestReview(float $latitude, float $longitude): array
    {
        $apiKey = config('services.google_maps.api_key');
        if (!filled($apiKey)) {
            throw new RuntimeException('Google Places API belum dikonfigurasi.');
        }

        try {
            $response = Http::timeout(15)
                ->withHeaders([
                    'X-Goog-Api-Key' => $apiKey,
                    'X-Goog-FieldMask' => 'places.id,places.displayName,places.formattedAddress,places.googleMapsUri',
                ])
                ->post('https://places.googleapis.com/v1/places:searchNearby', [
                    'languageCode' => 'id',
                    'regionCode' => 'ID',
                    'maxResultCount' => 1,
                    'rankPreference' => 'DISTANCE',
                    'locationRestriction' => [
                        'circle' => [
                            'center' => ['latitude' => $latitude, 'longitude' => $longitude],
                            'radius' => (float) config('services.google_maps.nearby_radius_meters', 150),
                        ],
                    ],
                ]);
        } catch (\Throwable $exception) {
            report($exception);
            throw new RuntimeException('Google Maps tidak dapat dihubungi. Coba lagi beberapa saat.', previous: $exception);
        }

        if (!$response->successful()) {
            report('Google Places Nearby Search failed with HTTP ' . $response->status());
            throw new RuntimeException('Pencarian tempat gagal. Periksa Places API, billing, dan pembatasan API key.');
        }

        $place = $response->json('places.0');
        if (!is_array($place) || blank($place['id'] ?? null)) {
            throw new RuntimeException('Tidak ada tempat Google Maps yang ditemukan di sekitar lokasi ini.');
        }

        return [
            'place_name' => $place['displayName']['text'] ?? 'Tempat terdekat',
            'place_address' => $place['formattedAddress'] ?? null,
            'review_url' => 'https://search.google.com/local/writereview?' . http_build_query(['placeid' => $place['id']]),
        ];
    }

    public function search(string $placeName, string $address): array
    {
        $apiKey = config('services.google_maps.api_key');
        if (!filled($apiKey)) {
            throw new RuntimeException('Google Places API belum dikonfigurasi.');
        }

        $address = trim($address);
        $mapsName = $this->placeNameFromMapsUrl($address);
        if (filter_var($address, FILTER_VALIDATE_URL) && $mapsName === null) {
            throw new RuntimeException('Link Google Maps tidak dikenali. Gunakan alamat atau link Google Maps lengkap.');
        }

        try {
            $response = Http::timeout(15)
                ->withHeaders([
                    'X-Goog-Api-Key' => $apiKey,
                    'X-Goog-FieldMask' => 'places.id,places.formattedAddress,places.googleMapsUri',
                ])
                ->post('https://places.googleapis.com/v1/places:searchText', [
                    'textQuery' => trim($placeName . ' ' . ($mapsName ?? $address)),
                    'languageCode' => 'id',
                    'regionCode' => 'ID',
                    'maxResultCount' => 1,
                ]);
        } catch (\Throwable $exception) {
            report($exception);
            throw new RuntimeException('Google Maps tidak dapat dihubungi. Coba lagi beberapa saat.', previous: $exception);
        }

        if (!$response->successful()) {
            report('Google Places API failed with HTTP ' . $response->status());
            throw new RuntimeException('Pencarian Google Maps gagal. Periksa Places API, billing, dan pembatasan API key.');
        }

        $place = $response->json('places.0');
        if (!is_array($place) || blank($place['id'] ?? null)) {
            throw new RuntimeException('Tempat tidak ditemukan. Periksa nama dan alamat tempat.');
        }

        return [
            'place_id' => $place['id'],
            'place_address' => $place['formattedAddress'] ?? ($mapsName ?? $address),
            'maps_url' => $place['googleMapsUri'] ?? null,
            'review_url' => 'https://search.google.com/local/writereview?' . http_build_query([
                'placeid' => $place['id'],
            ]),
        ];
    }

    private function placeNameFromMapsUrl(string $value): ?string
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
}
