<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PropertyCategory;
use App\Models\PropertyType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PropertyTypeController extends Controller
{
    public function index(Request $request)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $search = trim((string) $request->get('search', ''));

        $propertyTypes = $this->filteredQuery($search)
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.pcategorytype.index', compact('propertyTypes', 'search'));
    }

    public function search(Request $request)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $search = trim((string) $request->get('search', ''));

        $propertyTypes = $this->filteredQuery($search)
            ->orderByDesc('id')
            ->get()
            ->map(function (PropertyType $propertyType) {
                return [
                    'id' => $propertyType->id,
                    'name' => $propertyType->name,
                    'category_name' => $propertyType->propertyCategory?->name ?: '-',
                    'status' => $propertyType->status,
                    'status_label' => $propertyType->status ? 'Active' : 'Inactive',
                    'edit_url' => route('admin.property-types.edit', $propertyType),
                    'delete_url' => route('admin.property-types.destroy', $propertyType),
                    'toggle_status_url' => route('admin.property-types.toggle-status', $propertyType),
                ];
            })
            ->values();

        return response()->json([
            'data' => $propertyTypes,
        ]);
    }

    public function create()
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $propertyCategories = PropertyCategory::orderBy('name')->get();
        $propertyType = new PropertyType([
            'status' => true,
        ]);

        return view('admin.pcategorytype.add', compact('propertyCategories', 'propertyType'));
    }

    public function store(Request $request)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $data = $this->validatedData($request);

        PropertyType::create($data);

        return redirect()
            ->route('admin.property-types.index')
            ->with('success', 'Property Type created successfully.');
    }

    public function edit(PropertyType $propertyType)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $propertyCategories = PropertyCategory::orderBy('name')->get();

        return view('admin.pcategorytype.edit', compact('propertyType', 'propertyCategories'));
    }

    public function update(Request $request, PropertyType $propertyType)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $data = $this->validatedData($request, $propertyType);

        $propertyType->update($data);

        return redirect()
            ->route('admin.property-types.index')
            ->with('success', 'Property Type updated successfully.');
    }

    public function toggleStatus(PropertyType $propertyType)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $propertyType->update([
            'status' => !$propertyType->status,
        ]);

        $message = $propertyType->status ? 'Active' : 'Inactive';

        return redirect()
            ->route('admin.property-types.index')
            ->with('success', 'Property Type marked ' . $message . ' successfully.');
    }

    public function destroy(PropertyType $propertyType)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $propertyType->delete();

        return redirect()
            ->route('admin.property-types.index')
            ->with('success', 'Property Type deleted successfully.');
    }

    private function filteredQuery(string $search)
    {
        return PropertyType::with('propertyCategory:id,name')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('name', 'like', '%' . $search . '%')
                        ->orWhereHas('propertyCategory', function ($categoryQuery) use ($search) {
                            $categoryQuery->where('name', 'like', '%' . $search . '%');
                        });
                });
            });
    }

    private function validatedData(Request $request, ?PropertyType $propertyType = null): array
    {
        return $request->validate([
            'category_id' => ['required', 'exists:property_category,id'],
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('property_type', 'name')
                    ->ignore($propertyType?->id)
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
