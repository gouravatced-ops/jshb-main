<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $search = trim((string) $request->get('search', ''));

        $departments = Department::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('name', 'like', '%' . $search . '%')
                        ->orWhere('department_code', 'like', '%' . $search . '%');
                });
            })
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('admin.department.index', compact('departments', 'search'));
    }

    public function search(Request $request)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $search = trim((string) $request->get('search', ''));

        $departments = Department::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('name', 'like', '%' . $search . '%')
                        ->orWhere('department_code', 'like', '%' . $search . '%');
                });
            })
            ->orderByDesc('created_at')
            ->get()
            ->map(function (Department $department) {
                return [
                    'id' => $department->id,
                    'name' => $department->name,
                    'department_code' => $department->department_code ?: '-',
                    'status' => $department->status,
                    'status_label' => $department->status ? 'Active' : 'Inactive',
                    'created_at' => optional($department->created_at)->format('M d, Y') ?: '-',
                    'edit_url' => route('admin.departments.edit', $department),
                    'delete_url' => route('admin.departments.destroy', $department),
                ];
            })
            ->values();

        return response()->json([
            'data' => $departments,
        ]);
    }

    public function create()
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $department = new Department([
            'status' => true,
        ]);

        return view('admin.department.add', compact('department'));
    }

    public function store(Request $request)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        Department::create($this->validatedData($request));

        return redirect()
            ->route('admin.departments.index')
            ->with('success', 'Department created successfully.');
    }

    public function edit(Department $department)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        return view('admin.department.edit', compact('department'));
    }

    public function update(Request $request, Department $department)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $department->update($this->validatedData($request, $department));

        return redirect()
            ->route('admin.departments.index')
            ->with('success', 'Department updated successfully.');
    }

    public function destroy(Department $department)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $department->delete();

        return redirect()
            ->route('admin.departments.index')
            ->with('success', 'Department deleted successfully.');
    }

    private function validatedData(Request $request, ?Department $department = null): array
    {
        return $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('departments', 'name')
                    ->ignore($department?->id)
                    ->whereNull('deleted_at'),
            ],
            'department_code' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('departments', 'department_code')
                    ->ignore($department?->id)
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
