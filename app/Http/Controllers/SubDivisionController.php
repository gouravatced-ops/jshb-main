<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\SubDivision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class SubDivisionController extends Controller
{
    public function index(Request $request)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $search = trim((string) $request->get('search', ''));

        $subDivisions = SubDivision::with('division')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('name', 'like', '%' . $search . '%')
                        ->orWhere('subdivision_code', 'like', '%' . $search . '%')
                        ->orWhere('colony_name', 'like', '%' . $search . '%')
                        ->orWhere('locality_address', 'like', '%' . $search . '%')
                        ->orWhereHas('division', function ($divisionQuery) use ($search) {
                            $divisionQuery->where('name', 'like', '%' . $search . '%');
                        });
                });
            })
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        return view('admin.sub-division.index', compact('subDivisions', 'search'));
    }

    public function search(Request $request)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $search = trim((string) $request->get('search', ''));

        $subDivisions = SubDivision::with('division')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('name', 'like', '%' . $search . '%')
                        ->orWhere('subdivision_code', 'like', '%' . $search . '%')
                        ->orWhere('colony_name', 'like', '%' . $search . '%')
                        ->orWhere('locality_address', 'like', '%' . $search . '%')
                        ->orWhereHas('division', function ($divisionQuery) use ($search) {
                            $divisionQuery->where('name', 'like', '%' . $search . '%');
                        });
                });
            })
            ->orderByDesc('created_at')
            ->get()
            ->map(function (SubDivision $subDivision) {
                return [
                    'id' => $subDivision->id,
                    'name' => $subDivision->name,
                    'division_name' => $subDivision->division?->name ?: '-',
                    'subdivision_code' => $subDivision->subdivision_code ?: '-',
                    'colony_name' => $subDivision->colony_name ?: '-',
                    'locality_address' => $subDivision->locality_address ?: 'No address added',
                    'status' => $subDivision->status,
                    'status_label' => $subDivision->status ? 'Active' : 'Inactive',
                    'edit_url' => route('admin.sub-divisions.edit', $subDivision),
                    'delete_url' => route('admin.sub-divisions.destroy', $subDivision),
                    'toggle_status_url' => route('admin.sub-divisions.toggle-status', $subDivision),
                ];
            })
            ->values();

        return response()->json([
            'data' => $subDivisions,
        ]);
    }

    public function create()
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $subDivision = new SubDivision([
            'status' => true,
        ]);
        $divisions = Division::orderBy('name')->get();

        return view('admin.sub-division.add', compact('subDivision', 'divisions'));
    }

    public function store(Request $request)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $data = $this->validatedData($request);

        SubDivision::create($data);

        return redirect()
            ->route('admin.sub-divisions.index')
            ->with('success', 'Sub Division created successfully.');
    }

    public function edit(SubDivision $subDivision)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $divisions = Division::orderBy('name')->get();

        return view('admin.sub-division.edit', compact('subDivision', 'divisions'));
    }

    public function update(Request $request, SubDivision $subDivision)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $data = $this->validatedData($request, $subDivision);

        $subDivision->update($data);

        return redirect()
            ->route('admin.sub-divisions.index')
            ->with('success', 'Sub Division updated successfully.');
    }

    public function toggleStatus(SubDivision $subDivision)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $subDivision->update([
            'status' => !$subDivision->status,
        ]);

        return redirect()
            ->route('admin.sub-divisions.index')
            ->with('success', 'Sub Division status updated successfully.');
    }

    public function destroy(SubDivision $subDivision)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $subDivision->delete();

        return redirect()
            ->route('admin.sub-divisions.index')
            ->with('success', 'Sub Division deleted successfully.');
    }

    private function validatedData(Request $request, ?SubDivision $subDivision = null): array
    {
        return $request->validate([
            'division_id' => ['required', 'exists:divisions,id'],
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('sub_divisions', 'name')
                    ->ignore($subDivision?->id)
                    ->whereNull('deleted_at'),
            ],
            'subdivision_code' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('sub_divisions', 'subdivision_code')
                    ->ignore($subDivision?->id)
                    ->whereNull('deleted_at'),
            ],
            'colony_name' => ['nullable', 'string', 'max:255'],
            'locality_address' => ['nullable', 'string', 'max:255'],
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