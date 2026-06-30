<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\UserDetail;
use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class MemberController extends Controller
{
    private function superAdminGuard()
    {
        if ($redirect = $this->redirectIfLocked()) {
            return $redirect;
        }

        $user = Auth::user();

        if (!$user || $user->roleRelation?->slug !== 'super-admin') {
            return redirect()->route('admin.dashboard')->with('error', 'Super Admin access required.');
        }

        return null;
    }

    public function index(Request $request)
    {
        if ($redirect = $this->superAdminGuard()) {
            return $redirect;
        }

        $search = trim((string) $request->get('search', ''));

        $members = User::query()
            ->with(['roleRelation', 'detail', 'division'])
            ->whereDoesntHave('roleRelation', function ($query) {
                $query->where('slug', 'allottee');
            })
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%');
                });
            })
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        return view('admin.members.index', compact('members', 'search'));
    }

    public function create()
    {
        if ($redirect = $this->superAdminGuard()) {
            return $redirect;
        }

        $roles = Role::where('status', true)
            ->whereNotIn('slug', ['admin', 'super-admin', 'allottee'])
            ->orderBy('name')
            ->get();

        $divisions = Division::where('status', true)->orderBy('name')->get();

        return view('admin.members.create', compact('roles', 'divisions'));
    }

    public function store(Request $request)
    {
        if ($redirect = $this->superAdminGuard()) {
            return $redirect;
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role_id' => 'required|exists:roles,id',
            'division_id' => 'nullable|exists:divisions,id',
            'login_with_otp' => 'boolean',
            'phone' => 'nullable|string|max:20',
            'designation' => 'nullable|string|max:255',
        ]);

        $role = Role::findOrFail($request->role_id);
        if (in_array($role->slug, ['executive-engineer', 'division-officer']) && !$request->division_id) {
            return back()->withInput()->withErrors(['division_id' => 'The division field is required for the selected role.']);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->division_id ? User::generateUniqueUsername($request->division_id) : User::generateMemberUsername(),
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'division_id' => in_array($role->slug, ['operator', 'managing-director']) ? null : $request->division_id,
            'login_with_otp' => $request->boolean('login_with_otp'),
            'password_created_at' => now(),
            'status' => true,
        ]);

        UserDetail::create([
            'user_id' => $user->id,
            'phone' => $request->phone,
            'designation' => $request->designation,
        ]);

        return redirect()->route('admin.members.index')->with('success', 'Member created successfully.');
    }

    public function edit($id)
    {
        if ($redirect = $this->superAdminGuard()) {
            return $redirect;
        }

        $member = User::with('detail')->findOrFail($id);
        $roles = Role::where('status', true)
            ->whereNotIn('slug', ['admin', 'super-admin', 'allottee'])
            ->orderBy('name')
            ->get();

        $divisions = Division::where('status', true)->orderBy('name')->get();

        return view('admin.members.edit', compact('member', 'roles', 'divisions'));
    }

    public function update(Request $request, $id)
    {
        if ($redirect = $this->superAdminGuard()) {
            return $redirect;
        }

        $member = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($member->id)],
            'password' => 'nullable|string|min:6|confirmed',
            'role_id' => 'required|exists:roles,id',
            'division_id' => 'nullable|exists:divisions,id',
            'login_with_otp' => 'boolean',
            'phone' => 'nullable|string|max:20',
            'designation' => 'nullable|string|max:255',
        ]);

        $role = Role::findOrFail($request->role_id);
        if (in_array($role->slug, ['executive-engineer', 'division-officer']) && !$request->division_id) {
            return back()->withInput()->withErrors(['division_id' => 'The division field is required for the selected role.']);
        }

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'username' => $request->division_id ? User::generateUniqueUsername($request->division_id) : User::generateMemberUsername(),
            'division_id' => in_array($role->slug, ['operator', 'managing-director']) ? null : $request->division_id,
            'login_with_otp' => $request->boolean('login_with_otp'),
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $member->update($userData);

        UserDetail::updateOrCreate(
            ['user_id' => $member->id],
            [
                'phone' => $request->phone,
                'designation' => $request->designation,
            ]
        );

        return redirect()->route('admin.members.index')->with('success', 'Member updated successfully.');
    }

    public function destroy($id)
    {
        if ($redirect = $this->superAdminGuard()) {
            return $redirect;
        }

        $member = User::findOrFail($id);

        // Prevent self deletion
        if ($member->id === Auth::id()) {
            return redirect()->route('admin.members.index')->with('error', 'You cannot delete yourself.');
        }

        // Prevent deletion of admin, super-admin, and allottee
        if ($member->roleRelation && in_array($member->roleRelation->slug, ['admin', 'super-admin', 'allottee'])) {
            return redirect()->route('admin.members.index')->with('error', 'You cannot delete administrative or allottee accounts.');
        }

        $member->delete();

        return redirect()->route('admin.members.index')->with('success', 'Member deleted successfully.');
    }

    public function toggleStatus($id)
    {
        if ($redirect = $this->superAdminGuard()) {
            return $redirect;
        }

        $member = User::findOrFail($id);
        $member->status = !$member->status;
        $member->save();

        return redirect()->route('admin.members.index')->with('success', 'Member status updated successfully.');
    }
}
