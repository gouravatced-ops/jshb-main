<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use App\Models\FutureAllottee;
use App\Models\Scheme;
use App\Services\FutureAllotteeSyncService;

class FutureAllotteeSyncController extends Controller
{
    protected FutureAllotteeSyncService $syncService;

    public function __construct(FutureAllotteeSyncService $syncService)
    {
        $this->syncService = $syncService;
    }

    /**
     * Display listing of future/staging allottees with filters & metrics
     */
    public function index(Request $request)
    {
        $query = FutureAllottee::with([
            'contactDetail',
            'transaction',
            'scheme',
            'division',
            'subDivision',
            'propertyCategory',
            'syncedAllottee',
            'syncedApplication'
        ]);

        // Status Filter
        if ($request->filled('status')) {
            $query->where('sync_status', $request->status);
        }

        // Scheme Filter
        if ($request->filled('scheme_id')) {
            $query->where('scheme_id', $request->scheme_id);
        }

        // Search Filter (by ref, name, mobile, email, aadhar)
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('reference_portal_id', 'like', "%{$search}%")
                  ->orWhere('allottee_name', 'like', "%{$search}%")
                  ->orWhere('allottee_surname', 'like', "%{$search}%")
                  ->orWhere('allottee_name_hindi', 'like', "%{$search}%")
                  ->orWhere('mobile', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('aadhar_card_number', 'like', "%{$search}%");
            });
        }

        $allottees = $query->orderByRaw("FIELD(sync_status, 'pending', 'failed', 'synced')")
                           ->latest('id')
                           ->paginate(15)
                           ->withQueryString();

        // Metrics
        $totalCount   = FutureAllottee::count();
        $pendingCount = FutureAllottee::where('sync_status', 'pending')->count();
        $syncedCount  = FutureAllottee::where('sync_status', 'synced')->count();
        $failedCount  = FutureAllottee::where('sync_status', 'failed')->count();

        $schemes = Scheme::orderBy('scheme_name')->get();

        return view('admin.future_allottees.index', compact(
            'allottees',
            'totalCount',
            'pendingCount',
            'syncedCount',
            'failedCount',
            'schemes'
        ));
    }

    /**
     * Get single record details as JSON for modal preview
     */
    public function show($id)
    {
        $item = FutureAllottee::with([
            'contactDetail',
            'transaction',
            'scheme',
            'division',
            'subDivision',
            'propertyCategory',
            'quarterType',
            'syncedAllottee',
            'syncedApplication'
        ])->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data'   => $item,
        ]);
    }

    /**
     * Migrate single applicant to live system
     */
    public function syncSingle(Request $request, $id)
    {
        $item = FutureAllottee::findOrFail($id);
        $result = $this->syncService->syncSingle($item, $request->ip(), $request->userAgent());

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($result);
        }

        if ($result['status'] === 'success') {
            return back()->with('success', $result['message']);
        } elseif ($result['status'] === 'already_synced') {
            return back()->with('info', $result['message']);
        } else {
            return back()->with('error', $result['message']);
        }
    }

    /**
     * Bulk Migrate pending applicants
     */
    public function syncBatch(Request $request)
    {
        $ids = $request->input('ids', []);
        $result = $this->syncService->syncBatch((array)$ids);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status'  => 'success',
                'message' => "Batch sync completed: {$result['synced']} synced successfully, {$result['failed']} failed.",
                'result'  => $result,
            ]);
        }

        return back()->with('success', "Batch sync finished! Synced: {$result['synced']}, Failed: {$result['failed']}");
    }

    /**
     * Generate 10 mock future applications using seeder
     */
    public function seedSampleData(Request $request)
    {
        try {
            Artisan::call('db:seed', ['--class' => 'FutureAllotteeSeeder']);
            return back()->with('success', '10 realistic sample citizen applications generated successfully in staging!');
        } catch (\Exception $e) {
            return back()->with('error', 'Seeding failed: ' . $e->getMessage());
        }
    }
}
