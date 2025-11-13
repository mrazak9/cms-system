<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:activity-log.view');
    }

    public function index(Request $request)
    {
        $query = ActivityLog::with('user')->latest();

        // Filter by log type
        if ($request->has('type') && $request->type) {
            $query->where('log_type', $request->type);
        }

        // Filter by user
        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        // Search
        if ($request->has('search') && $request->search) {
            $query->where('description', 'like', "%{$request->search}%");
        }

        $logs = $query->paginate(50);

        // Get statistics
        $stats = [
            'total' => ActivityLog::count(),
            'today' => ActivityLog::whereDate('created_at', today())->count(),
            'this_week' => ActivityLog::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
        ];

        return view('admin.activity-logs.index', compact('logs', 'stats'));
    }
}
