<?php

namespace App\Http\Controllers;

use App\Models\ParentOrganization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ParentOrganizationController extends Controller
{
    public function index(Request $request)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $search = trim((string) $request->get('search', ''));

        $parentOrganizations = ParentOrganization::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('name', 'like', '%' . $search . '%')
                        ->orWhere('state', 'like', '%' . $search . '%')
                        ->orWhere('district', 'like', '%' . $search . '%')
                        ->orWhere('pin_code', 'like', '%' . $search . '%')
                        ->orWhere('post_office', 'like', '%' . $search . '%');
                });
            })
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('admin.parent_organization.index', compact('parentOrganizations', 'search'));
    }

    public function search(Request $request)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $search = trim((string) $request->get('search', ''));

        $parentOrganizations = ParentOrganization::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('name', 'like', '%' . $search . '%')
                        ->orWhere('state', 'like', '%' . $search . '%')
                        ->orWhere('district', 'like', '%' . $search . '%')
                        ->orWhere('pin_code', 'like', '%' . $search . '%')
                        ->orWhere('post_office', 'like', '%' . $search . '%');
                });
            })
            ->orderByDesc('created_at')
            ->get()
            ->map(function (ParentOrganization $organization) {
                return [
                    'id' => $organization->id,
                    'name' => $organization->name,
                    'state' => $organization->state ?: '-',
                    'district' => $organization->district ?: '-',
                    'pin_code' => $organization->pin_code ?: '-',
                    'locality' => $organization->locality ?: ($organization->post_office ?: 'No locality added'),
                    'status' => $organization->status,
                    'status_label' => $organization->status ? 'Active' : 'Inactive',
                    'edit_url' => route('admin.parent-organizations.edit', $organization),
                    'delete_url' => route('admin.parent-organizations.destroy', $organization),
                ];
            })
            ->values();

        return response()->json([ 'data' => $parentOrganizations ]);
    }

    public function create()
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $parentOrganization = new ParentOrganization(['status' => true]);
        $districts = getDistrictsByStateId(15);
        return view('admin.parent_organization.add', compact('parentOrganization', 'districts'));
    }

    public function store(Request $request)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $data = $this->validatedData($request);
        ParentOrganization::create($data);

        return redirect()->route('admin.parent-organizations.index')->with('success', 'Parent organization created successfully.');
    }

    public function edit(ParentOrganization $parentOrganization)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }
        $districts = getDistrictsByStateId(15);
        return view('admin.parent_organization.edit', compact('parentOrganization', 'districts'));
    }

    public function update(Request $request, ParentOrganization $parentOrganization)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $data = $this->validatedData($request, $parentOrganization);
        $parentOrganization->update($data);

        return redirect()->route('admin.parent-organizations.index')->with('success', 'Parent organization updated successfully.');
    }

    public function destroy(ParentOrganization $parentOrganization)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $parentOrganization->delete();

        return redirect()->route('admin.parent-organizations.index')->with('success', 'Parent organization deleted successfully.');
    }

    private function validatedData(Request $request, ?ParentOrganization $parentOrganization = null): array
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('parent_organizations', 'name')
                    ->ignore($parentOrganization?->id)
                    ->whereNull('deleted_at'),
            ],
            'display_code' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('parent_organizations', 'display_code')
                    ->ignore($parentOrganization?->id)
                    ->whereNull('deleted_at'),
            ],
            'address' => ['nullable', 'string'],
            'pin_code' => ['nullable', 'string', 'max:20'],
            'state' => ['nullable', 'string', 'max:255'],
            'district' => ['nullable', 'string', 'max:255'],
            'locality' => ['nullable', 'string', 'max:255'],
            'police_station' => ['nullable', 'string', 'max:255'],
            'post_office' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'boolean'],
        ]);

        $validated['status'] = $request->boolean('status');

        return $validated;
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
