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

        $type = $request->get('type', 'all');

        $members = User::query()
            ->with(['roleRelation', 'detail', 'division'])
            ->whereDoesntHave('roleRelation', function ($query) {
                $query->where('slug', 'allottee');
            })
            ->when($type === 'divisional', function ($query) {
                $query->whereNotNull('division_id');
            })
            ->when($type === 'non_divisional', function ($query) {
                $query->whereNull('division_id');
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

        return view('admin.members.index', compact('members', 'search', 'type'));
    }

    public function create()
    {
        if ($redirect = $this->superAdminGuard()) {
            return $redirect;
        }

        $takenRoleIds = User::whereNotNull('role_id')->pluck('role_id')->toArray();
        $restrictedSlugs = ['managing-director', 'revenue-officer', 'chief-accounts-officer', 'chief-financial-officer', 'secretary-chief-engineer'];

        $roles = Role::where('status', true)
            ->whereNotIn('slug', ['admin', 'super-admin', 'allottee'])
            ->orderBy('name')
            ->get()
            ->filter(function ($role) use ($takenRoleIds, $restrictedSlugs) {
                return !(in_array($role->slug, $restrictedSlugs) && in_array($role->id, $takenRoleIds));
            })
            ->values();

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

        // Restrict specific roles to a single account
        $restrictedSlugs = ['managing-director', 'revenue-officer', 'chief-accounts-officer', 'chief-financial-officer', 'secretary-chief-engineer', 'super-admin'];
        if (in_array($role->slug, $restrictedSlugs)) {
            $exists = User::where('role_id', $role->id)->exists();
            if ($exists) {
                return back()->withInput()->withErrors(['role_id' => 'An account for ' . $role->name . ' already exists. Only one is allowed.']);
            }
        }

        if (in_array($role->slug, ['executive-engineer', 'division-officer']) && !$request->division_id) {
            return back()->withInput()->withErrors(['division_id' => 'The division field is required for the selected role.']);
        }

        $isDefault = $request->boolean('is_default');
        $divisionId = in_array($role->slug, ['operator', 'managing-director', 'revenue-officer', 'chief-accounts-officer', 'chief-financial-officer', 'secretary-chief-engineer']) ? null : $request->division_id;

        if (!$isDefault) {
            $existingCount = User::where('role_id', $request->role_id)
                ->where('division_id', $divisionId)
                ->count();
            if ($existingCount === 0) {
                $isDefault = true;
            }
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->division_id ? User::generateUniqueUsername($request->division_id) : User::generateMemberUsername(),
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'division_id' => $divisionId,
            'login_with_otp' => $request->boolean('login_with_otp'),
            'password_created_at' => now(),
            'status' => true,
            'is_default' => $isDefault,
        ]);

        if ($isDefault) {
            User::where('role_id', $request->role_id)
                ->where('division_id', $divisionId)
                ->where('id', '!=', $user->id)
                ->update(['is_default' => false]);
        }

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
        $takenRoleIds = User::whereNotNull('role_id')->where('id', '!=', $id)->pluck('role_id')->toArray();
        $restrictedSlugs = ['managing-director', 'revenue-officer', 'chief-accounts-officer', 'chief-financial-officer', 'secretary-chief-engineer'];

        $roles = Role::where('status', true)
            ->whereNotIn('slug', ['admin', 'super-admin', 'allottee'])
            ->orderBy('name')
            ->get()
            ->filter(function ($role) use ($takenRoleIds, $restrictedSlugs) {
                return !(in_array($role->slug, $restrictedSlugs) && in_array($role->id, $takenRoleIds));
            })
            ->values();

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

        // Restrict specific roles to a single account
        $restrictedSlugs = ['managing-director', 'revenue-officer', 'chief-accounts-officer', 'chief-financial-officer', 'secretary-chief-engineer', 'super-admin'];
        if (in_array($role->slug, $restrictedSlugs)) {
            $exists = User::where('role_id', $role->id)->where('id', '!=', $id)->exists();
            if ($exists) {
                return back()->withInput()->withErrors(['role_id' => 'An account for ' . $role->name . ' already exists. Only one is allowed.']);
            }
        }

        if (in_array($role->slug, ['executive-engineer', 'division-officer']) && !$request->division_id) {
            return back()->withInput()->withErrors(['division_id' => 'The division field is required for the selected role.']);
        }

        if($member->username == NULL){
            $member->username = $request->division_id ? User::generateUniqueUsername($request->division_id) : User::generateMemberUsername();
        }

        $isDefault = $request->boolean('is_default');
        $divisionId = in_array($role->slug, ['operator', 'managing-director', 'revenue-officer', 'chief-accounts-officer', 'chief-financial-officer', 'secretary-chief-engineer']) ? null : $request->division_id;

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'division_id' => $divisionId,
            'login_with_otp' => $request->boolean('login_with_otp'),
            'is_default' => $isDefault,
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $member->update($userData);

        if ($isDefault) {
            User::where('role_id', $request->role_id)
                ->where('division_id', $divisionId)
                ->where('id', '!=', $member->id)
                ->update(['is_default' => false]);
        }

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

    public function testNotify($id)
    {
        if ($redirect = $this->superAdminGuard()) {
            return response()->json(['error' => 'Super Admin access required.'], 403);
        }

        $member = User::findOrFail($id);
        
        $notificationService = app(\App\Services\NotificationService::class);
        $notificationService->send([
            'user_id' => $member->id,
            'subject' => 'Test Realtime Notification',
            'message' => 'This is a real-time notification test sent by the Super Admin.',
            'link' => '#',
            'type' => 'test',
            'send_email' => false
        ]);

        return response()->json(['success' => 'Test notification sent successfully to ' . $member->name]);
    }
}
