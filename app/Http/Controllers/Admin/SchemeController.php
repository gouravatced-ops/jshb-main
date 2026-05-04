<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Scheme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class SchemeController extends Controller
{
    public function index(Request $request)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $search = trim((string) $request->get('search', ''));

        $schemes = Scheme::query()
            ->with(['division', 'subDivision', 'propertyCategory', 'propertyType', 'propertySubType', 'quarterType'])
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

        return view('admin.schemes.index', compact('schemes', 'search'));
    }

    public function search(Request $request)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $search = trim((string) $request->get('search', ''));

        $schemes = Scheme::query()
            ->with(['division', 'subDivision', 'propertyCategory', 'propertyType', 'propertySubType', 'quarterType'])
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
                    'total_units' => $scheme->total_units,
                    'scheme_start_date' => $scheme->scheme_start_date ?? '-',
                    'scheme_end_date' => $scheme->scheme_end_date ?? '-',
                    'created_at' => optional($scheme->created_at)->format('M d, Y') ?: '-',
                    'edit_url' => route('admin.schemes.edit', $scheme),
                    'delete_url' => route('admin.schemes.destroy', $scheme),
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
        $propertyCategories = \App\Models\PropertyCategory::orderBy('name')->get();
        $propertyTypes = \App\Models\PropertyType::orderBy('name')->get();
        $propertySubTypes = \App\Models\PropertyMainType::orderBy('name')->get();
        $quarterTypes = \App\Models\QuarterType::orderBy('name')->get();

        return view('admin.schemes.add', compact('scheme', 'divisions', 'propertyCategories', 'propertyTypes', 'propertySubTypes', 'quarterTypes'));
    }

    public function store(Request $request)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $validated = $request->validate([
            'division_id' => 'required|exists:divisions,id',
            'sub_division_id' => 'nullable|exists:sub_divisions,id',
            'pcategory_id' => 'required|exists:property_categories,id',
            'p_type_id' => 'required|exists:property_types,id',
            'p_sub_type_id' => 'nullable|exists:property_main_types,id',
            'quarter_type_id' => 'nullable|exists:quarter_types,id',
            'scheme_name' => 'required|string|max:255',
            'scheme_name_hindi' => 'nullable|string|max:255',
            'scheme_code' => 'required|string|max:100|unique:schemes,scheme_code',
            'total_units' => 'required|integer|min:0',
            'lease_period' => 'nullable|integer|min:0',
            'initiation_year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'scheme_start_date' => 'nullable|date',
            'scheme_end_date' => 'nullable|date|after_or_equal:scheme_start_date',
        ]);

        $validated['created_by'] = Auth::id();

        $scheme = Scheme::create($validated);

        return redirect()
            ->route('admin.schemes.index')
            ->with('success', 'Scheme created successfully.');
    }

    public function edit(Scheme $scheme)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        // Load required data for dropdowns
        $divisions = \App\Models\Division::orderBy('name')->get();
        $propertyCategories = \App\Models\PropertyCategory::orderBy('name')->get();
        $propertyTypes = \App\Models\PropertyType::orderBy('name')->get();
        $propertySubTypes = \App\Models\PropertyMainType::orderBy('name')->get();
        $quarterTypes = \App\Models\QuarterType::orderBy('name')->get();

        return view('admin.schemes.edit', compact('scheme', 'divisions', 'propertyCategories', 'propertyTypes', 'propertySubTypes', 'quarterTypes'));
    }

    public function update(Request $request, Scheme $scheme)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $validated = $request->validate([
            'division_id' => 'required|exists:divisions,id',
            'sub_division_id' => 'nullable|exists:sub_divisions,id',
            'pcategory_id' => 'required|exists:property_categories,id',
            'p_type_id' => 'required|exists:property_types,id',
            'p_sub_type_id' => 'nullable|exists:property_main_types,id',
            'quarter_type_id' => 'nullable|exists:quarter_types,id',
            'scheme_name' => 'required|string|max:255',
            'scheme_name_hindi' => 'nullable|string|max:255',
            'scheme_code' => 'required|string|max:100|unique:schemes,scheme_code,' . $scheme->id,
            'total_units' => 'required|integer|min:0',
            'lease_period' => 'nullable|integer|min:0',
            'initiation_year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'scheme_start_date' => 'nullable|date',
            'scheme_end_date' => 'nullable|date|after_or_equal:scheme_start_date',
        ]);

        $validated['updated_by'] = Auth::id();

        $scheme->update($validated);

        return redirect()
            ->route('admin.schemes.index')
            ->with('success', 'Scheme updated successfully.');
    }

    public function destroy(Scheme $scheme)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        // Check if scheme has related records before deleting
        if ($scheme->blocks()->exists() || 
            $scheme->quotas()->exists() || 
            $scheme->financial()->exists() || 
            $scheme->quarterFees()->exists()) {
            return redirect()
                ->route('admin.schemes.index')
                ->with('error', 'Cannot delete scheme with existing blocks, quotas, or fees.');
        }

        $scheme->delete();

        return redirect()
            ->route('admin.schemes.index')
            ->with('success', 'Scheme deleted successfully.');
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