<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminActivityLogController extends Controller
{
    public function index(Request $request): View
    {
        $query = ActivityLog::with('user')->latest();

        if ($request->filled('admin')) {
            $query->where('user_id', $request->integer('admin'));
        }
        if ($request->filled('method')) {
            $query->where('method', $request->string('method')->upper()->value());
        }

        $activities = $query->paginate(50)->withQueryString();

        return view('admin.activity-logs.index', compact('activities'));
    }

    public function show(ActivityLog $activityLog): View
    {
        $activityLog->load('user');

        return view('activity-logs.show', compact('activityLog'));
    }
}
