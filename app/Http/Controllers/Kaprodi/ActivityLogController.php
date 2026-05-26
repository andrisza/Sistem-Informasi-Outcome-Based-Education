<?php

namespace App\Http\Controllers\Kaprodi;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')->orderByDesc('created_at');

        if ($search = $request->search) {
            $query->where(fn ($q) =>
                $q->where('action', 'like', "%{$search}%")
                  ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$search}%"))
            );
        }

        if ($action = $request->action) {
            $query->where('action', $action);
        }

        if ($from = $request->from) {
            $query->whereDate('created_at', '>=', $from);
        }

        if ($to = $request->to) {
            $query->whereDate('created_at', '<=', $to);
        }

        $logs    = $query->paginate(30)->withQueryString();
        $actions = ActivityLog::select('action')->distinct()->orderBy('action')->pluck('action');

        return view('kaprodi.activity-log.index', compact('logs', 'actions'));
    }
}
