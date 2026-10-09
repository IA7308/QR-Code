<?php

namespace App\Http\Controllers;

use App\Services\GooglePlaceLookup;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use RuntimeException;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class UniversalQrController extends Controller
{
    public function show(): View
    {
        return view('qr.universal');
    }

    public function nfcReader(): View
    {
        return view('qr.nfc-reader');
    }

    public function locate(Request $request, GooglePlaceLookup $lookup): JsonResponse
    {
        $coordinates = $request->validate([
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
        ]);

        try {
            return response()->json($lookup->nearestReview(
                (float) $coordinates['latitude'],
                (float) $coordinates['longitude'],
            ));
        } catch (RuntimeException $exception) {
            return response()->json(['message' => $exception->getMessage()], 422);
        }
    }

    public function adminPrint(): View
    {
        $url = route('qr.universal.show');

        return view('qr.admin.universal', [
            'url' => $url,
            'qrSvg' => QrCode::format('svg')->size(320)->margin(2)->generate($url),
        ]);
    }

    public function adminPrintNfcReader(): View
    {
        $url = route('qr.nfc-reader.show');

        return view('qr.admin.nfc-reader', [
            'url' => $url,
            'qrSvg' => QrCode::format('svg')->size(320)->margin(2)->generate($url),
        ]);
    }
}
