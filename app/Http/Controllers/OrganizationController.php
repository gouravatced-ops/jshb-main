<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\ParentOrganization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class OrganizationController extends Controller
{
    public function index(Request $request)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $search = trim((string) $request->get('search', ''));

        $organizations = $this->buildOrganizationQuery($request)
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('admin.organization.index', compact('organizations', 'search'))
            ->with('showParentOnly', false)
            ->with('indexRoute', route('admin.organizations.index'))
            ->with('searchRoute', route('admin.organizations.search'));
    }

    public function parentIndex(Request $request)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $search = trim((string) $request->get('search', ''));

        $organizations = $this->buildOrganizationQuery($request, true)
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('admin.organization.index', compact('organizations', 'search'))
            ->with('showParentOnly', true)
            ->with('indexRoute', route('admin.parent-organizations.index'))
            ->with('searchRoute', route('admin.parent-organizations.search'));
    }

    public function search(Request $request)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $organizations = $this->buildOrganizationQuery($request)
            ->get()
            ->map(function (Organization $organization) {
                return [
                    'id' => $organization->id,
                    'name' => $organization->name,
                    'parent_name' => $organization->parentOrganization->display_code ?? $organization->parentOrganization->name ?? '-',
                    'district_wise_posting' => $organization->district_wise_posting ? 'Yes' : 'No',
                    'state' => $organization->state ?: '-',
                    'district' => $organization->district ?: '-',
                    'pin_code' => $organization->pin_code ?: '-',
                    'locality' => $organization->locality ?: ($organization->post_office ?: 'No locality added'),
                    'status' => $organization->status,
                    'status_label' => $organization->status ? 'Active' : 'Inactive',
                    'edit_url' => route('admin.organizations.edit', $organization),
                    'delete_url' => route('admin.organizations.destroy', $organization),
                ];
            })
            ->values();

        return response()->json([
            'data' => $organizations,
        ]);
    }

    public function parentSearch(Request $request)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $organizations = $this->buildOrganizationQuery($request, true)
            ->get()
            ->map(function (Organization $organization) {
                return [
                    'id' => $organization->id,
                    'name' => $organization->name,
                    'parent_name' => $organization->parentOrganization->name ?? '-',
                    'district_wise_posting' => $organization->district_wise_posting ? 'Yes' : 'No',
                    'state' => $organization->state ?: '-',
                    'district' => $organization->district ?: '-',
                    'pin_code' => $organization->pin_code ?: '-',
                    'locality' => $organization->locality ?: ($organization->post_office ?: 'No locality added'),
                    'status' => $organization->status,
                    'status_label' => $organization->status ? 'Active' : 'Inactive',
                    'edit_url' => route('admin.organizations.edit', $organization),
                    'delete_url' => route('admin.organizations.destroy', $organization),
                ];
            })
            ->values();

        return response()->json([
            'data' => $organizations,
        ]);
    }

    public function create()
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $organization = new Organization([
            'status' => true,
            'district_wise_posting' => false,
        ]);
        $districts = getDistrictsByStateId(15);
        $parentOrganizations = ParentOrganization::orderBy('name')->get();
        return view('admin.organization.add', compact('organization', 'districts', 'parentOrganizations'));
    }

    public function store(Request $request)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $data = $this->validatedData($request);
        Organization::create($data);

        return redirect()
            ->route('admin.organizations.index')
            ->with('success', 'Organization created successfully.');
    }

    public function edit(Organization $organization)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $parentOrganizations = ParentOrganization::orderBy('name')->get();
        $districts = getDistrictsByStateId(15);
        return view('admin.organization.edit', compact('organization', 'parentOrganizations', 'districts'));
    }

    public function update(Request $request, Organization $organization)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $data = $this->validatedData($request, $organization);
        $organization->update($data);

        return redirect()
            ->route('admin.organizations.index')
            ->with('success', 'Organization updated successfully.');
    }

    public function destroy(Organization $organization)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $organization->delete();

        return redirect()
            ->route('admin.organizations.index')
            ->with('success', 'Organization deleted successfully.');
    }

    private function validatedData(Request $request, ?Organization $organization = null): array
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('organizations', 'name')
                    ->ignore($organization?->id)
                    ->whereNull('deleted_at'),
            ],
            'display_code' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('organizations', 'display_code')
                    ->ignore($organization?->id)
                    ->whereNull('deleted_at'),
            ],
            'address' => ['nullable', 'string'],
            'pin_code' => ['nullable', 'string', 'max:20'],
            'state' => ['nullable', 'string', 'max:255'],
            'district' => ['nullable', 'string', 'max:255'],
            'locality' => ['nullable', 'string', 'max:255'],
            'police_station' => ['nullable', 'string', 'max:255'],
            'post_office' => ['nullable', 'string', 'max:255'],
            'parent_organization_id' => ['nullable', 'integer', 'exists:parent_organizations,id'],
            'district_wise_posting' => ['nullable', 'boolean'],
            'status' => ['nullable', 'boolean'],
            'address' => ['nullable', 'string'],
            'pin_code' => ['nullable', 'string', 'max:20'],
            'state' => ['nullable', 'string', 'max:255'],
            'district' => ['nullable', 'string', 'max:255'],
            'locality' => ['nullable', 'string', 'max:255'],
            'police_station' => ['nullable', 'string', 'max:255'],
            'post_office' => ['nullable', 'string', 'max:255'],
            'parent_organization_id' => ['nullable', 'integer', 'exists:parent_organizations,id'],
            'district_wise_posting' => ['nullable', 'boolean'],
            'status' => ['nullable', 'boolean'],
        ]);

        $validated['status'] = $request->boolean('status');
        $validated['district_wise_posting'] = $request->boolean('district_wise_posting');

        return $validated;
    }

    private function buildOrganizationQuery(Request $request)
    {
        $search = trim((string) $request->get('search', ''));

        return Organization::with('parentOrganization')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('name', 'like', '%' . $search . '%')
                        ->orWhere('state', 'like', '%' . $search . '%')
                        ->orWhere('district', 'like', '%' . $search . '%')
                        ->orWhere('pin_code', 'like', '%' . $search . '%')
                        ->orWhere('post_office', 'like', '%' . $search . '%')
                        ->orWhereHas('parentOrganization', function ($parentQuery) use ($search) {
                            $parentQuery->where('name', 'like', '%' . $search . '%');
                            $parentQuery->where('display_code', 'like', '%' . $search . '%');
                        });
                });
            });
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
