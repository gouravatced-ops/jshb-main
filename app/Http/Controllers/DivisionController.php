<?php

namespace App\Http\Controllers;

use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class DivisionController extends Controller
{
    public function index(Request $request)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $search = trim((string) $request->get('search', ''));

        $divisions = Division::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('name', 'like', '%' . $search . '%')
                        ->orWhere('division_code', 'like', '%' . $search . '%');
                });
            })
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        return view('admin.division.index', compact('divisions', 'search'));
    }

    public function search(Request $request)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $search = trim((string) $request->get('search', ''));

        $divisions = Division::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('name', 'like', '%' . $search . '%')
                        ->orWhere('division_code', 'like', '%' . $search . '%');
                });
            })
            ->orderByDesc('created_at')
            ->get()
            ->map(function (Division $division) {
                return [
                    'id' => $division->id,
                    'name' => $division->name,
                    'division_code' => $division->division_code ?: '-',
                    'status' => $division->status,
                    'status_label' => $division->status ? 'Active' : 'Inactive',
                    'created_at' => optional($division->created_at)->format('M d, Y') ?: '-',
                    'edit_url' => route('admin.divisions.edit', $division),
                    'delete_url' => route('admin.divisions.destroy', $division),
                    'toggle_status_url' => route('admin.divisions.toggle-status', $division),
                ];
            })
            ->values();

        return response()->json([
            'data' => $divisions,
        ]);
    }

    public function create()
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $division = new Division([
            'status' => true,
        ]);

        return view('admin.division.add', compact('division'));
    }

    public function store(Request $request)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $data = $this->validatedData($request);

        Division::create($data);

        return redirect()
            ->route('admin.divisions.index')
            ->with('success', 'Division created successfully.');
    }

    public function edit(Division $division)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        return view('admin.division.edit', compact('division'));
    }

    public function update(Request $request, Division $division)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $data = $this->validatedData($request, $division);

        $division->update($data);

        return redirect()
            ->route('admin.divisions.index')
            ->with('success', 'Division updated successfully.');
    }

    public function toggleStatus(Division $division)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $division->update([
            'status' => !$division->status,
        ]);

        return redirect()
            ->route('admin.divisions.index')
            ->with('success', 'Division status updated successfully.');
    }

    public function destroy(Division $division)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $division->delete();

        return redirect()
            ->route('admin.divisions.index')
            ->with('success', 'Division deleted successfully.');
    }

    private function validatedData(Request $request, ?Division $division = null): array
    {
        return $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('divisions', 'name')
                    ->ignore($division?->id)
                    ->whereNull('deleted_at'),
            ],
            'division_code' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('divisions', 'division_code')
                    ->ignore($division?->id)
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
