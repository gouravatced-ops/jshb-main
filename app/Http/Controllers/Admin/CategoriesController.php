<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PropertyCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CategoriesController extends Controller
{
    public function index(Request $request)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $search = trim((string) $request->get('search', ''));

        $categories = PropertyCategory::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('name', 'like', '%' . $search . '%');
                });
            })
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        return view('admin.categories.index', compact('categories', 'search'));
    }

    public function search(Request $request)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $search = trim((string) $request->get('search', ''));

        $categories = PropertyCategory::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('name', 'like', '%' . $search . '%');
                });
            })
            ->orderByDesc('created_at')
            ->get()
            ->map(function (PropertyCategory $categories) {
                return [
                    'id' => $categories->id,
                    'name' => $categories->name,
                    'status' => $categories->status,
                    'status_label' => $categories->status ? 'Active' : 'Inactive',
                    'created_at' => optional($categories->created_at)->format('M d, Y') ?: '-',
                    'edit_url' => route('admin.categories.edit', $categories),
                    'delete_url' => route('admin.categories.destroy', $categories),
                    'toggle_status_url' => route('admin.categories.toggle-status', $categories),
                ];
            })
            ->values();

        return response()->json([
            'data' => $categories,
        ]);
    }

    public function create()
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $categories = new PropertyCategory([
            'status' => true,
        ]);

        return view('admin.categories.add', compact('categories'));
    }

    public function store(Request $request)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $data = $this->validatedData($request);

        PropertyCategory::create($data);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function edit(PropertyCategory $categories)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        return view('admin.categories.edit', compact('categories'));
    }

    public function update(Request $request, PropertyCategory $categories)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $data = $this->validatedData($request, $categories);

        $categories->update($data);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function toggleStatus(PropertyCategory $categories)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $categories->update([
            'status' => !$categories->status,
        ]);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category status updated successfully.');
    }

    public function destroy(PropertyCategory $categories)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $categories->delete();

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category deleted successfully.');
    }

    private function validatedData(Request $request, ?PropertyCategory $categories = null): array
    {
        return $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('property_category', 'name')
                    ->ignore($categories?->id)
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
