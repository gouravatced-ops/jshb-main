<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PropertyMainType;
use App\Models\PropertyType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PropertyMainTypeController extends Controller
{
    public function index(Request $request)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $search = trim((string) $request->get('search', ''));

        $propertySubTypes = $this->filteredQuery($search)
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.propertysubtypes.index', compact('propertySubTypes', 'search'));
    }

    public function search(Request $request)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $search = trim((string) $request->get('search', ''));

        $propertySubTypes = $this->filteredQuery($search)
            ->orderByDesc('id')
            ->get()
            ->map(function (PropertyMainType $propertySubType) {
                return [
                    'id' => $propertySubType->id,
                    'name' => $propertySubType->name,
                    'type_name' => $propertySubType->propertyType?->name ?: '-',
                    'category_name' => $propertySubType->propertyType?->propertyCategory?->name ?: '-',
                    'status' => $propertySubType->status,
                    'status_label' => $propertySubType->status ? 'Active' : 'Inactive',
                    'edit_url' => route('admin.property-sub-types.edit', $propertySubType),
                    'delete_url' => route('admin.property-sub-types.destroy', $propertySubType),
                    'toggle_status_url' => route('admin.property-sub-types.toggle-status', $propertySubType),
                ];
            })
            ->values();

        return response()->json([
            'data' => $propertySubTypes,
        ]);
    }

    public function create()
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $propertyTypes = PropertyType::orderBy('name')->get();
        $propertySubType = new PropertyMainType([
            'status' => true,
        ]);

        return view('admin.propertysubtypes.add', compact('propertyTypes', 'propertySubType'));
    }

    public function store(Request $request)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $data = $this->validatedData($request);

        PropertyMainType::create($data);

        return redirect()
            ->route('admin.property-sub-types.index')
            ->with('success', 'Property Sub Type created successfully.');
    }

    public function edit(PropertyMainType $propertySubType)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $propertyTypes = PropertyType::orderBy('name')->get();

        return view('admin.propertysubtypes.edit', compact('propertySubType', 'propertyTypes'));
    }

    public function update(Request $request, PropertyMainType $propertySubType)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $data = $this->validatedData($request, $propertySubType);

        $propertySubType->update($data);

        return redirect()
            ->route('admin.property-sub-types.index')
            ->with('success', 'Property Sub Type updated successfully.');
    }

    public function toggleStatus(PropertyMainType $propertySubType)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $propertySubType->update([
            'status' => !$propertySubType->status,
        ]);

        $message = $propertySubType->status ? 'Active' : 'Inactive';

        return redirect()
            ->route('admin.property-sub-types.index')
            ->with('success', 'Property Sub Type marked ' . $message . ' successfully.');
    }

    public function destroy(PropertyMainType $propertySubType)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $propertySubType->delete();

        return redirect()
            ->route('admin.property-sub-types.index')
            ->with('success', 'Property Sub Type deleted successfully.');
    }

    private function filteredQuery(string $search)
    {
        return PropertyMainType::with([
            'propertyType:id,name,category_id',
            'propertyType.propertyCategory:id,name',
        ])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('name', 'like', '%' . $search . '%')
                        ->orWhereHas('propertyType', function ($typeQuery) use ($search) {
                            $typeQuery->where('name', 'like', '%' . $search . '%')
                                ->orWhereHas('propertyCategory', function ($categoryQuery) use ($search) {
                                    $categoryQuery->where('name', 'like', '%' . $search . '%');
                                });
                        });
                });
            });
    }

    private function validatedData(Request $request, ?PropertyMainType $propertySubType = null): array
    {
        return $request->validate([
            'ptype_id' => ['required', 'exists:property_type,id'],
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('property_sub_type', 'name')
                    ->ignore($propertySubType?->id)
                    ->whereNull('deleted_at'),
            ],
            'status' => ['required', 'boolean'],
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
