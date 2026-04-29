<?php

namespace App\Http\Controllers;

use App\Models\BlockList;
use App\Models\District;
use App\Models\GuestHouseRequisition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class GuestHouseRequisitionController extends Controller
{
    public function userIndex()
    {
        $user = Auth::user();

        $requisitions = GuestHouseRequisition::query()
            ->with(['district', 'block', 'approver'])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('requisition.user-index', compact('requisitions'));
    }

    public function create()
    {
        $user = Auth::user();
        $engineer = $user->engineerDetail?->load(['district', 'block', 'department', 'postType', 'currentOrganization']);

        $districts = District::where('state_id', 15)->orderBy('name_en')->get();
        $blocks = BlockList::with('district')->orderBy('block_name_eng')->get();

        return view('requisition.create', compact('engineer', 'districts', 'blocks'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $engineer = $user->engineerDetail;

        if (! $engineer) {
            throw ValidationException::withMessages([
                'requisition' => 'Employee details are required before submitting a guest house requisition.',
            ]);
        }

        $validated = $request->validate([
            'district_id' => ['required', 'exists:districts,id'],
            'block_id' => ['required', 'exists:block_lists,id'],
            'guest_house_name' => ['required', 'string', 'max:255'],
            'purpose' => ['required', 'string', 'max:1000'],
            'stay_from' => ['required', 'date'],
            'stay_to' => ['required', 'date', 'after_or_equal:stay_from'],
            'total_guests' => ['required', 'integer', 'min:1', 'max:20'],
            'remarks' => ['nullable', 'string', 'max:1000'],
        ]);

        $block = BlockList::find($validated['block_id']);

        if ($block && (int) $block->district_id !== (int) $validated['district_id']) {
            throw ValidationException::withMessages([
                'block_id' => 'Selected block does not belong to the chosen district.',
            ]);
        }

        GuestHouseRequisition::create([
            'user_id' => $user->id,
            'engineer_detail_id' => $engineer->id,
            'district_id' => $validated['district_id'],
            'block_id' => $validated['block_id'],
            'guest_house_name' => $validated['guest_house_name'],
            'purpose' => $validated['purpose'],
            'stay_from' => $validated['stay_from'],
            'stay_to' => $validated['stay_to'],
            'total_guests' => $validated['total_guests'],
            'remarks' => $validated['remarks'] ?? null,
            'status' => 'pending',
        ]);

        return redirect()
            ->route('requisitions.index')
            ->with('success', 'Guest house requisition submitted successfully.');
    }

    public function adminIndex()
    {
        $requisitions = GuestHouseRequisition::query()
            ->with(['user', 'engineer.department', 'district', 'block', 'approver'])
            ->latest()
            ->paginate(10);

        return view('requisition.admin-index', compact('requisitions'));
    }

    public function updateStatus(Request $request, GuestHouseRequisition $requisition)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:approved,rejected'],
            'admin_remarks' => ['nullable', 'string', 'max:1000'],
        ]);

        $requisition->update([
            'status' => $validated['status'],
            'admin_remarks' => $validated['admin_remarks'] ?? null,
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return redirect()
            ->route('admin.requisitions.index')
            ->with('success', 'Requisition status updated successfully.');
    }
}
