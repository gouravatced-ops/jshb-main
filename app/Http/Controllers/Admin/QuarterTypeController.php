<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuarterType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class QuarterTypeController extends Controller
{
    public function index(Request $request)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $search = trim((string) $request->get('search', ''));

        $quarterTypes = $this->filteredQuery($search)
            ->orderBy('display_order')
            ->orderBy('quarter_name')
            ->paginate(20)
            ->withQueryString();

        return view('admin.quarters.index', compact('quarterTypes', 'search'));
    }

    public function search(Request $request)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $search = trim((string) $request->get('search', ''));

        $quarterTypes = $this->filteredQuery($search)
            ->orderBy('display_order')
            ->orderBy('quarter_name')
            ->get()
            ->map(function (QuarterType $quarterType) {
                return [
                    'id' => $quarterType->quarter_id,
                    'quarter_code' => $quarterType->quarter_code,
                    'quarter_name' => $quarterType->quarter_name,
                    'quarter_full_name' => $quarterType->quarter_full_name ?: '-',
                    'income_range' => $quarterType->income_range,
                    'display_order' => $quarterType->display_order,
                    'status' => $quarterType->status,
                    'status_label' => $quarterType->status == 1 ? 'Active' : 'Inactive',
                    'edit_url' => route('admin.quarter-types.edit', $quarterType),
                    'delete_url' => route('admin.quarter-types.destroy', $quarterType),
                    'toggle_status_url' => route('admin.quarter-types.toggle-status', $quarterType),
                ];
            })
            ->values();

        return response()->json([
            'data' => $quarterTypes,
        ]);
    }

    public function create()
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $quarterType = new QuarterType([
            'status' => 1,
            'display_order' => 0,
        ]);

        return view('admin.quarters.add', compact('quarterType'));
    }

    public function store(Request $request)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $data = $this->validatedData($request);

        QuarterType::create($data);

        return redirect()
            ->route('admin.quarter-types.index')
            ->with('success', 'Quarter Type created successfully.');
    }

    public function edit(QuarterType $quarterType)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        return view('admin.quarters.edit', compact('quarterType'));
    }

    public function update(Request $request, QuarterType $quarterType)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $data = $this->validatedData($request, $quarterType);

        $quarterType->update($data);

        return redirect()
            ->route('admin.quarter-types.index')
            ->with('success', 'Quarter Type updated successfully.');
    }

    public function toggleStatus(QuarterType $quarterType)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $quarterType->update([
            'status' => $quarterType->status == 1 ? 0 : 1,
        ]);

        return redirect()
            ->route('admin.quarter-types.index')
            ->with('success', 'Quarter Type status updated successfully.');
    }

    public function destroy(QuarterType $quarterType)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $quarterType->delete();

        return redirect()
            ->route('admin.quarter-types.index')
            ->with('success', 'Quarter Type deleted successfully.');
    }

    private function filteredQuery(string $search)
    {
        return QuarterType::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('quarter_code', 'like', '%' . $search . '%')
                        ->orWhere('quarter_name', 'like', '%' . $search . '%')
                        ->orWhere('quarter_full_name', 'like', '%' . $search . '%');
                });
            });
    }

    private function validatedData(Request $request, ?QuarterType $quarterType = null): array
    {
        return $request->validate([
            'quarter_code' => [
                'required',
                'string',
                'max:10',
                Rule::unique('quarter_type', 'quarter_code')
                    ->ignore($quarterType?->quarter_id, 'quarter_id')
                    ->whereNull('deleted_at'),
            ],
            'quarter_name' => ['required', 'string', 'max:100'],
            'quarter_full_name' => ['nullable', 'string', 'max:200'],
            'min_income' => ['nullable', 'numeric', 'min:0'],
            'max_income' => ['nullable', 'numeric', 'min:0', 'gte:min_income'],
            'display_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', 'in:0,1'],
        ]);
    }

    private function adminGuard()
    {
        if ($redirect = $this->redirectIfLocked()) {
            return $redirect;
        }

        $user = Auth::user();

        if (! $user || $user->role !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'Admin access required.');
        }

        return null;
    }
}
