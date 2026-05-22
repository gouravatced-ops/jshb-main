<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Scheme;
use App\Models\SchemeBlock;
use App\Models\SchemeUnitQuota;
use App\Models\QuotaType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SchemeController extends Controller
{
    public function index(Request $request)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $search = trim((string) $request->get('search', ''));

        $schemes = Scheme::query()
            ->with(['division', 'subDivision', 'propertyCategory', 'propertyType', 'propertySubType', 'quarterType', 'financial', 'quarterFees'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('scheme_name', 'like', '%' . $search . '%')
                        ->orWhere('scheme_name_hindi', 'like', '%' . $search . '%')
                        ->orWhere('scheme_code', 'like', '%' . $search . '%');
                });
            })
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();
        // return $schemes;
        return view('admin.schemes.index', compact('schemes', 'search'));
    }

    public function search(Request $request)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $search = trim((string) $request->get('search', ''));

        $schemes = Scheme::query()
            ->with(['division', 'subDivision', 'propertyCategory', 'propertyType', 'propertySubType', 'quarterType', 'financial', 'quarterFees'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('scheme_name', 'like', '%' . $search . '%')
                        ->orWhere('scheme_name_hindi', 'like', '%' . $search . '%')
                        ->orWhere('scheme_code', 'like', '%' . $search . '%');
                });
            })
            ->orderByDesc('created_at')
            ->get()
            ->map(function (Scheme $scheme) {
                return [
                    'id' => $scheme->id,
                    'scheme_name' => $scheme->scheme_name,
                    'scheme_name_hindi' => $scheme->scheme_name_hindi,
                    'scheme_code' => $scheme->scheme_code,
                    'division' => $scheme->division?->name ?? '-',
                    'sub_division' => $scheme->subDivision?->name ?? '-',
                    'property_category' => $scheme->propertyCategory?->name ?? '-',
                    'property_type' => $scheme->propertyType?->name ?? '-',
                    'initiation_year' => $scheme->initiation_year ?? '-',
                    'quarter_code' => $scheme->quarterType?->quarter_code ?? '-',
                    'property_total_cost' => $scheme->financial?->property_total_cost ?? '-',
                    'down_payment_amount' => $scheme->financial?->down_payment_amount ?? '-',
                    'emi_without_penalty' => $scheme->financial?->emi_without_penalty ?? '-',
                    'emi_count' => $scheme->financial?->emi_count ?? '-',
                    'down_payment_percentage' => $scheme->financial?->down_payment_percentage ?? '-',
                    'total_units' => $scheme->total_units,
                    'scheme_start_date' => optional($scheme->scheme_start_date)->format('M d, Y') ?: '-',
                    'scheme_end_date' => optional($scheme->scheme_end_date)->format('M d, Y') ?: '-',
                    'created_at' => optional($scheme->created_at)->format('M d, Y') ?: '-',
                    'edit_url' => route('admin.schemes.edit', $scheme),
                    'delete_url' => route('admin.schemes.destroy', $scheme),
                    'block_url' => route('admin.schemes.blocks.index', $scheme),
                    'quota_url' => route('admin.schemes.quotas.index', $scheme),
                ];
            })
            ->values();

        return response()->json([
            'data' => $schemes,
        ]);
    }

    public function create()
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $scheme = new Scheme();

        // Load required data for dropdowns
        $divisions = \App\Models\Division::orderBy('name')->get();
        $subDivisions = \App\Models\SubDivision::orderBy('name')->get();
        $propertyCategories = \App\Models\PropertyCategory::orderBy('name')->get();
        $propertyTypes = \App\Models\PropertyType::orderBy('name')->get();
        $propertySubTypes = \App\Models\PropertyMainType::orderBy('name')->get();
        $quarterTypes = \App\Models\QuarterType::orderBy('quarter_id')->get();

        return view('admin.schemes.add', compact('scheme', 'divisions', 'subDivisions', 'propertyCategories', 'propertyTypes', 'propertySubTypes', 'quarterTypes'));
    }

    public function store(Request $request)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        DB::beginTransaction();

        try {

            Log::info('Scheme Store Request Received', [
                'user_id' => Auth::id(),
                'payload' => $request->all()
            ]);

            $validated = $request->validate([

                // Scheme
                'division_id' => 'required|exists:divisions,id',
                'sub_division_id' => 'nullable|exists:sub_divisions,id',
                'pcategory_id' => 'required|exists:property_category,id',
                'p_type_id' => 'required|exists:property_type,id',
                'p_sub_type_id' => 'nullable|exists:property_sub_type,id',
                'quarter_type_id' => 'nullable|exists:quarter_type,quarter_id',
                'scheme_name' => 'required|string|max:255',
                'scheme_name_hindi' => 'nullable|string|max:255',
                'scheme_code' => 'required|string|max:100|unique:schemes,scheme_code',
                'total_units' => 'required|integer|min:1',

                'lease_period' => 'nullable|integer',
                'initiation_year' => 'nullable|integer|min:1900|max:' . date('Y'),
                'scheme_start_date' => 'nullable|date',
                'scheme_end_date' => 'nullable|date|after_or_equal:scheme_start_date',

                // Financial
                'property_total_cost' => 'required|numeric|min:0',
                'down_payment_percentage' => 'required|numeric|min:0',
                'emi_count' => 'required|integer|min:1',

                // Quarter Fees
                'quarter_fees' => 'required|array',
                'quarter_fees.*.quarter_type_id' => 'required',
                'quarter_fees.*.application_fee' => 'required|numeric|min:0',
                'quarter_fees.*.emd_amount' => 'required|numeric|min:0',
            ]);

            Log::info('Validation Passed');

            $validated['created_by'] = Auth::id();

            $scheme = Scheme::create($validated);

            Log::info('Scheme Created', ['scheme_id' => $scheme->id]);

            $scheme->financial()->create([
                'property_total_cost'     => $request->property_total_cost,
                'lottery_percentage'      => $request->lottery_percentage,
                'lottery_amount'          => $request->lottery_amount,
                'allotment_percentage'    => $request->allotment_percentage,
                'allotement_amount'       => $request->allotement_amount,
                'balance_amount'          => $request->balance_amount,
                'emi_count'               => $request->emi_count,
                'normal_interest_rate'    => $request->normal_interest_rate,
                'emi_without_penalty'     => $request->emi_without_penalty,
                'penalty_interest_rate'   => $request->penalty_interest_rate,
                'emi_with_penalty'        => $request->emi_with_penalty,
                'admin_charges'           => $request->admin_charges,
            ]);

            Log::info('Financial Data Saved');

            foreach ($request->quarter_fees as $fee) {

                $scheme->quarterFees()->create([
                    'quarter_type_id' => $fee['quarter_type_id'],
                    'application_fee' => $fee['application_fee'] ?? 0,
                    'emd_amount'      => $fee['emd_amount'] ?? 0,
                ]);
            }

            Log::info('Quarter Fees Saved');

            DB::commit();

            Log::info('Scheme Store Transaction Committed');

            return redirect()
                ->route('admin.schemes.index')
                ->with('success', 'Scheme created successfully.');
        } catch (\Exception $e) {

            DB::rollBack();

            Log::error('Scheme Store Failed', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'user_id' => Auth::id(),
                'payload' => $request->all()
            ]);

            return back()
                ->with('error', 'Something went wrong! Check logs.')
                ->withInput();
        }
    }

    public function edit(Scheme $scheme)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        // Load required data for dropdowns
        $divisions = \App\Models\Division::orderBy('name')->get();
        $subDivisions = \App\Models\SubDivision::orderBy('name')->get();
        $propertyCategories = \App\Models\PropertyCategory::orderBy('name')->get();
        $propertyTypes = \App\Models\PropertyType::orderBy('name')->get();
        $propertySubTypes = \App\Models\PropertyMainType::orderBy('name')->get();
        $quarterTypes = \App\Models\QuarterType::orderBy('quarter_id')->get();
        $scheme->load([
            'division',
            'subDivision',
            'propertyCategory',
            'propertyType',
            'propertySubType',
            'quarterType',
            'financial',
            'quarterFees'
        ]);
        // return $scheme;
        return view('admin.schemes.edit', compact('scheme', 'divisions', 'subDivisions', 'propertyCategories', 'propertyTypes', 'propertySubTypes', 'quarterTypes'));
    }

    public function update(Request $request, Scheme $scheme)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        DB::beginTransaction();

        try {

            $validated = $request->validate([

                // Scheme
                'division_id' => 'required|exists:divisions,id',
                'sub_division_id' => 'nullable|exists:sub_divisions,id',
                'pcategory_id' => 'required|exists:property_category,id',
                'p_type_id' => 'required|exists:property_type,id',
                'p_sub_type_id' => 'nullable|exists:property_sub_type,id',
                'quarter_type_id' => 'nullable|exists:quarter_type,quarter_id',
                'scheme_name' => 'required|string|max:255',
                'scheme_name_hindi' => 'nullable|string|max:255',
                'scheme_code' => 'required|string|max:100|unique:schemes,scheme_code,' . $scheme->id,
                'total_units' => 'required|integer|min:1',

                'lease_period' => 'nullable|integer',
                'initiation_year' => 'nullable|integer|min:1900|max:' . date('Y'),
                'scheme_start_date' => 'nullable|date',
                'scheme_end_date' => 'nullable|date|after_or_equal:scheme_start_date',

                // Financial
                'property_total_cost' => 'required|numeric|min:0',
                'down_payment_percentage' => 'required|numeric|min:0',
                'emi_count' => 'required|integer|min:1',

                // Quarter Fees
                'quarter_fees' => 'required|array',
                'quarter_fees.*.quarter_type_id' => 'required',
                'quarter_fees.*.application_fee' => 'required|numeric|min:0',
                'quarter_fees.*.emd_amount' => 'required|numeric|min:0',
            ]);

            $validated['updated_by'] = Auth::id();

            $scheme->update($validated);

            $scheme->financial()->updateOrCreate(
                ['scheme_id' => $scheme->id],
                [
                    'property_total_cost'     => $request->property_total_cost,
                    'lottery_percentage'      => $request->lottery_percentage,
                    'lottery_amount'          => $request->lottery_amount,
                    'allotment_percentage'    => $request->allotment_percentage,
                    'allotement_amount'       => $request->allotement_amount,
                    'balance_amount'          => $request->balance_amount,
                    'emi_count'               => $request->emi_count,
                    'normal_interest_rate'    => $request->normal_interest_rate,
                    'emi_without_penalty'     => $request->emi_without_penalty,
                    'penalty_interest_rate'   => $request->penalty_interest_rate,
                    'emi_with_penalty'        => $request->emi_with_penalty,
                    'admin_charges'           => $request->admin_charges,
                ]
            );

            foreach ($request->quarter_fees as $fee) {

                $scheme->quarterFees()->updateOrCreate(
                    [
                        'scheme_id'       => $scheme->id,
                        'quarter_type_id' => $fee['quarter_type_id']
                    ],
                    [
                        'application_fee' => $fee['application_fee'] ?? 0,
                        'emd_amount'      => $fee['emd_amount'] ?? 0,
                    ]
                );
            }

            DB::commit();

            return redirect()
                ->route('admin.schemes.index')
                ->with('success', 'Scheme updated successfully.');
        } catch (\Exception $e) {

            DB::rollBack();

            return back()
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Scheme $scheme)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        try {
            $scheme->delete();

            Log::info('Scheme soft deleted', [
                'scheme_id' => $scheme->id
            ]);

            return redirect()
                ->route('admin.schemes.index')
                ->with('success', 'Scheme deleted successfully.');
        } catch (\Exception $e) {

            Log::error('Scheme delete failed', [
                'scheme_id' => $scheme->id,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Delete failed');
        }
    }

    public function blocksIndex(Request $request, Scheme $scheme)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $search = trim((string) $request->get('search', ''));

        $blocks = SchemeBlock::query()
            ->where('scheme_id', $scheme->id)
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('block_name', 'like', '%' . $search . '%')
                        ->orWhere('scheme_property_type', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('created_at', 'asc')
            ->paginate(20)
            ->withQueryString();

        return view('admin.schemes.blocks.index', compact('scheme', 'blocks', 'search'));
    }

    public function blocksSearch(Request $request, Scheme $scheme)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $search = trim((string) $request->get('search', ''));

        $blocks = SchemeBlock::query()
            ->where('scheme_id', $scheme->id)
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('block_name', 'like', '%' . $search . '%')
                        ->orWhere('scheme_property_type', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function (SchemeBlock $block) use ($scheme) {
                return [
                    'id' => $block->id,
                    'block_name' => $block->block_name,
                    'scheme_property_type' => $block->scheme_property_type,
                    'area_sqft' => $block->area_sqft,
                    'undivided_land_share' => $block->undivided_land_share,
                    'total_buildup' => $block->total_buildup,
                    'total_area_of_construction' => $block->total_area_of_construction,
                    'dimension_east' => $block->dimension_east,
                    'dimension_west' => $block->dimension_west,
                    'dimension_north' => $block->dimension_north,
                    'dimension_south' => $block->dimension_south,
                    'status' => $block->status,
                    'edit_url' => route('admin.schemes.blocks.edit', [$scheme, $block]),
                    'delete_url' => route('admin.schemes.blocks.destroy', [$scheme, $block]),
                ];
            })
            ->values();

        return response()->json([
            'data' => $blocks,
            'total' => $blocks->count(),
        ]);
    }

    public function blocksCreate(Scheme $scheme)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        // Check if scheme has reached maximum blocks limit
        $maxBlocks = 10;
        $currentBlocks = SchemeBlock::where('scheme_id', $scheme->id)->count();

        if ($currentBlocks >= $maxBlocks) {
            return redirect()
                ->route('admin.schemes.blocks.index', $scheme)
                ->with('error', 'Maximum limit of ' . $maxBlocks . ' blocks reached for this scheme.');
        }

        $block = new SchemeBlock(['status' => true]);
        $scheme->load(['propertyType']);
        // return $scheme;
        return view('admin.schemes.blocks.add', compact('scheme', 'block', 'currentBlocks'));
    }

    public function blocksStore(Request $request, Scheme $scheme)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        // Check if scheme has reached maximum blocks limit
        $maxBlocks = 10;
        $currentBlocks = SchemeBlock::where('scheme_id', $scheme->id)->count();

        if ($currentBlocks >= $maxBlocks) {
            return redirect()
                ->route('admin.schemes.blocks.index', $scheme)
                ->with('error', 'Maximum limit of ' . $maxBlocks . ' blocks reached for this scheme.');
        }

        $validated = $request->validate([
            'block_name' => 'required|string|max:255',
            'scheme_property_type' => 'nullable|string|max:100',
            'area_sqft' => 'required|numeric|min:0.01',
            'undivided_land_share' => 'nullable|numeric|min:0',
            'total_buildup' => 'nullable|numeric|min:0',
            'total_area_of_construction' => 'nullable|numeric|min:0',
            'dimension_east' => 'nullable|string|max:50',
            'dimension_west' => 'nullable|string|max:50',
            'dimension_north' => 'nullable|string|max:50',
            'dimension_south' => 'nullable|string|max:50',
            'arm_east_west_north' => 'nullable|string|max:50',
            'arm_east_west_south' => 'nullable|string|max:50',
            'arm_north_south_east' => 'nullable|string|max:50',
            'arm_north_south_west' => 'nullable|string|max:50',
            'status' => 'boolean',
        ]);

        $validated['scheme_id'] = $scheme->id;
        $validated['created_by'] = Auth::id();

        SchemeBlock::create($validated);

        return redirect()
            ->route('admin.schemes.blocks.index', $scheme)
            ->with('success', 'Block "' . $validated['block_name'] . '" created successfully.');
    }

    public function blocksEdit(Scheme $scheme, SchemeBlock $block)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        // Ensure block belongs to scheme
        if ($block->scheme_id !== $scheme->id) {
            return redirect()
                ->route('admin.schemes.blocks.index', $scheme)
                ->with('error', 'Block not found in this scheme.');
        }

        // Load creator and updater relationships
        $block->load(['creator', 'updater']);

        return view('admin.schemes.blocks.edit', compact('scheme', 'block'));
    }

    public function blocksUpdate(Request $request, Scheme $scheme, SchemeBlock $block)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        // Ensure block belongs to scheme
        if ($block->scheme_id !== $scheme->id) {
            return redirect()
                ->route('admin.schemes.blocks.index', $scheme)
                ->with('error', 'Block not found in this scheme.');
        }

        $validated = $request->validate([
            'block_name' => 'required|string|max:255',
            'scheme_property_type' => 'nullable|string|max:100',
            'area_sqft' => 'required|numeric|min:0.01',
            'undivided_land_share' => 'nullable|numeric|min:0',
            'total_buildup' => 'nullable|numeric|min:0',
            'total_area_of_construction' => 'nullable|numeric|min:0',
            'dimension_east' => 'nullable|string|max:50',
            'dimension_west' => 'nullable|string|max:50',
            'dimension_north' => 'nullable|string|max:50',
            'dimension_south' => 'nullable|string|max:50',
            'arm_east_west_north' => 'nullable|string|max:50',
            'arm_east_west_south' => 'nullable|string|max:50',
            'arm_north_south_east' => 'nullable|string|max:50',
            'arm_north_south_west' => 'nullable|string|max:50',
            'status' => 'boolean',
        ]);

        $validated['updated_by'] = Auth::id();

        $block->update($validated);

        return redirect()
            ->route('admin.schemes.blocks.index', $scheme)
            ->with('success', 'Block "' . $validated['block_name'] . '" updated successfully.');
    }

    public function blocksDestroy(Scheme $scheme, SchemeBlock $block)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        // Ensure block belongs to scheme
        if ($block->scheme_id !== $scheme->id) {
            return redirect()
                ->route('admin.schemes.blocks.index', $scheme)
                ->with('error', 'Block not found in this scheme.');
        }

        $blockName = $block->block_name;
        $block->delete();

        return redirect()
            ->route('admin.schemes.blocks.index', $scheme)
            ->with('success', 'Block "' . $blockName . '" deleted successfully.');
    }

    public function quotasIndex(Scheme $scheme)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        // Get all quota types
        $quotaTypes = QuotaType::orderBy('id')->get();

        // Get existing quotas for this scheme
        $quotas = SchemeUnitQuota::where('scheme_id', $scheme->id)->get()->keyBy('quota_type_id');

        $totalAllocatedUnits = $quotas->sum('allotted_units');
        $remainingUnits = $scheme->total_units - $totalAllocatedUnits;

        return view('admin.schemes.quotas.index', compact('scheme', 'quotaTypes', 'quotas', 'totalAllocatedUnits', 'remainingUnits'));
    }

    public function quotasBulkUpdate(Request $request, Scheme $scheme)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $validated = $request->validate([
            'quotas' => 'required|array',
            'quotas.*.quota_type_id' => 'required|exists:quota_types,id',
            'quotas.*.total_units' => 'required|integer|min:0',
            'quotas.*.allotted_units' => 'nullable|integer|min:0',
            'quotas.*.id' => 'nullable|exists:scheme_unit_quotas,id',
        ]);

        // Calculate total units to validate against scheme capacity
        $totalUnits = 0;
        foreach ($validated['quotas'] as $quota) {
            $totalUnits += $quota['total_units'];
        }

        if ($totalUnits > $scheme->total_units) {
            return redirect()
                ->back()
                ->with('error', 'Total allocated units (' . $totalUnits . ') exceeds scheme capacity (' . $scheme->total_units . ').');
        }

        DB::beginTransaction();

        try {
            foreach ($validated['quotas'] as $quotaData) {
                $allottedUnits = $quotaData['allotted_units'] ?? 0;

                // Validate allotted units don't exceed total units
                if ($allottedUnits > $quotaData['total_units']) {
                    $quotaType = QuotaType::find($quotaData['quota_type_id']);
                    throw new \Exception("Allotted units for {$quotaType->name} cannot exceed total units.");
                }

                if (isset($quotaData['id']) && !empty($quotaData['id'])) {
                    // Update existing quota
                    $quota = SchemeUnitQuota::where('scheme_id', $scheme->id)
                        ->where('id', $quotaData['id'])
                        ->first();

                    if ($quota) {
                        $quota->update([
                            'total_units' => $quotaData['total_units'],
                            'allotted_units' => $allottedUnits,
                            'updated_by' => Auth::id(),
                        ]);
                    }
                } else if ($quotaData['total_units'] > 0) {
                    // Create new quota only if total_units > 0
                    SchemeUnitQuota::create([
                        'scheme_id' => $scheme->id,
                        'quota_type_id' => $quotaData['quota_type_id'],
                        'total_units' => $quotaData['total_units'],
                        'allotted_units' => $allottedUnits,
                        'created_by' => Auth::id(),
                    ]);
                }
            }

            // Delete quotas that were set to 0 total_units and have no ID
            // This is handled by not creating/updating them

            DB::commit();

            return redirect()
                ->route('admin.schemes.quotas.index', $scheme)
                ->with('success', 'Quota allocations updated successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Error saving quotas: ' . $e->getMessage());
        }
    }

    private function adminGuard()
    {
        if ($redirect = $this->redirectIfLocked()) {
            return $redirect;
        }

        $user = Auth::user();

        if (!$user || $user->role !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'Admin access required.');
        }

        return null;
    }
}
