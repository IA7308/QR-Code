<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClientActivityLogController extends Controller
{
    public function index(Request $request): View
    {
        $activities = ActivityLog::query()
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(30);

        return view('qr.client.activity-logs', compact('activities'));
    }

    public function show(Request $request, ActivityLog $activityLog): View
    {
        abort_unless($activityLog->user_id === $request->user()->id, 404);

        return view('activity-logs.show', compact('activityLog'));
    }
}
