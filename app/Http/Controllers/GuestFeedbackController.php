<?php

namespace App\Http\Controllers;

use App\Models\GuestFeedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class GuestFeedbackController extends Controller
{
    public function index(Request $request): View
    {
        $feedback = GuestFeedback::query()
            ->with(['place:id,user_id,name', 'unit:id,qr_client_id,unit_code', 'destination:id,place_name'])
            ->when(Auth::user()->role !== 'admin', function ($query): void {
                $query->whereHas('place', fn ($placeQuery) => $placeQuery->where('user_id', Auth::id()));
            })
            ->latest()
            ->paginate(20);

        return view('feedback.index', compact('feedback'));
    }

    public function clientIndex(Request $request): View
    {
        $client = $request->user()->qrClient()->where('status', 'ACTIVE')->firstOrFail();
        $feedback = GuestFeedback::query()
            ->with(['unit:id,qr_client_id,unit_code', 'destination:id,place_name'])
            ->whereHas('unit', fn ($query) => $query->where('qr_client_id', $client->id))
            ->latest()
            ->paginate(20);

        return view('feedback.index', compact('feedback'));
    }
}
