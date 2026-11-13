<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of the activity logs.
     */
    public function index(Request $request)
    {
        $query = Activity::with('causer')->latest();

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('log_name', 'like', "%{$search}%")
                  ->orWhere('subject_type', 'like', "%{$search}%");
            });
        }

        if ($request->filled('event')) {
            $query->where('event', $request->event);
        }

        $totalCount = Activity::count();
        $createdCount = Activity::where('event', 'created')->count();
        $updatedCount = Activity::where('event', 'updated')->count();
        $deletedCount = Activity::where('event', 'deleted')->count();

        $activities = $query->paginate(20)->withQueryString();

        return view('admin.activity_logs.index', compact('activities', 'totalCount', 'createdCount', 'updatedCount', 'deletedCount'));
    }
}
