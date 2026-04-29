<?php

namespace App\Http\Controllers;

use App\Models\BlockList;
use App\Models\Department;
use App\Models\District;
use App\Models\Division;
use App\Models\EngineerDetail;
use App\Models\Organization;
use App\Models\PostType;
use App\Models\SubDivision;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class EngineerController extends Controller
{
    public function index(Request $request)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $search = trim((string) $request->get('search', ''));

        $engineers = EngineerDetail::query()
            ->with([
                'user',
                'currentOrganization',
                'parentOrganization',
                'district',
                'block',
                'division',
                'subDivision',
                'postType',
                'department',
            ])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('employee_name', 'like', '%' . $search . '%')
                        ->orWhere('employee_hindi_name', 'like', '%' . $search . '%')
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('email', 'like', '%' . $search . '%');
                        })
                        ->orWhereHas('currentOrganization', function ($organizationQuery) use ($search) {
                            $organizationQuery->where('name', 'like', '%' . $search . '%');
                        })
                        ->orWhereHas('postType', function ($postTypeQuery) use ($search) {
                            $postTypeQuery->where('name', 'like', '%' . $search . '%');
                        })
                        ->orWhereHas('department', function ($departmentQuery) use ($search) {
                            $departmentQuery->where('name', 'like', '%' . $search . '%');
                        });
                });
            })
            ->orderBy('employee_name')
            ->paginate(20)
            ->withQueryString();

        return view('admin.engineer.index', compact('engineers', 'search'));
    }

    public function search(Request $request)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $search = trim((string) $request->get('search', ''));

        $engineers = EngineerDetail::query()
            ->with(['user', 'currentOrganization', 'district', 'block', 'postType', 'department'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('employee_name', 'like', '%' . $search . '%')
                        ->orWhere('employee_hindi_name', 'like', '%' . $search . '%')
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('email', 'like', '%' . $search . '%');
                        })
                        ->orWhereHas('currentOrganization', function ($organizationQuery) use ($search) {
                            $organizationQuery->where('name', 'like', '%' . $search . '%');
                        })
                        ->orWhereHas('postType', function ($postTypeQuery) use ($search) {
                            $postTypeQuery->where('name', 'like', '%' . $search . '%');
                        })
                        ->orWhereHas('department', function ($departmentQuery) use ($search) {
                            $departmentQuery->where('name', 'like', '%' . $search . '%');
                        });
                });
            })
            ->orderBy('employee_name')
            ->get()
            ->map(function (EngineerDetail $engineer) {
                return [
                    'id' => $engineer->id,
                    'employee_name' => $engineer->employee_name,
                    'state_government_engineer_id' => $engineer->masked_state_government_engineer_id,
                    'email' => $engineer->user?->email ?: '-',
                    'current_organization' => $engineer->currentOrganization?->name ?: '-',
                    'department' => $engineer->department?->name ?: '-',
                    'post_type' => $engineer->postType?->name ?: '-',
                    'district' => $engineer->district?->name_en ?: '-',
                    'block' => $engineer->block?->block_name_eng ?: '-',
                    'edit_url' => route('admin.engineers.edit', $engineer->encrypted_route_key),
                ];
            })
            ->values();

        return response()->json([
            'data' => $engineers,
        ]);
    }

    public function create()
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $engineer = new EngineerDetail();
        $user = new User([
            'role' => 'user',
        ]);

        return view('admin.engineer.add', $this->formViewData($engineer, $user));
    }

    public function store(Request $request)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $validated = $this->validatedData($request);

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'name' => $validated['employee_name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'user',
            ]);

            $user->detail()->create([
                'phone' => $validated['phone'] ?? null,
            ]);

            EngineerDetail::create($this->engineerPayload($validated, $user->id));
        });

        return redirect()
            ->route('admin.engineers.index')
            ->with('success', 'Engineer created successfully.');
    }

    public function edit(string $engineer)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $engineer = $this->resolveEngineer($engineer);
        $engineer->load(['user', 'user.detail']);

        return view('admin.engineer.edit', $this->formViewData($engineer, $engineer->user));
    }

    public function update(Request $request, string $engineer)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $engineer = $this->resolveEngineer($engineer);
        $engineer->load('user');
        $validated = $this->validatedData($request, $engineer);

        DB::transaction(function () use ($engineer, $validated) {
            $userPayload = [
                'name' => $validated['employee_name'],
                'email' => $validated['email'],
                'role' => 'user',
            ];

            if (! empty($validated['password'])) {
                $userPayload['password'] = Hash::make($validated['password']);
            }

            $engineer->user->update($userPayload);
            $userDetail = $engineer->user->detail ?? new UserDetail(['user_id' => $engineer->user_id]);

            if (array_key_exists('phone', $validated) && filled($validated['phone'])) {
                $userDetail->phone = $validated['phone'];
            }

            $userDetail->user_id = $engineer->user_id;
            $userDetail->save();

            $engineer->update($this->engineerPayload($validated, $engineer->user_id, $engineer));
        });

        return redirect()
            ->route('admin.engineers.index')
            ->with('success', 'Engineer updated successfully.');
    }

    public function verifySensitive(Request $request, string $engineer)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $request->validate([
            'pin' => ['required', 'digits:5'],
        ]);

        $admin = Auth::user();

        if (! $admin || ! Hash::check($request->pin, $admin->secure_pin ?? '')) {
            return response()->json([
                'message' => 'Invalid security PIN.',
            ], 422);
        }

        $engineer = $this->resolveEngineer($engineer);
        $engineer->load('user.detail');

        return response()->json([
            'data' => [
                'state_government_engineer_id' => $engineer->state_government_engineer_id,
                'aadhar_no' => $engineer->aadhar_no,
                'pan_card_no' => $engineer->pan_card_no,
                'phone' => $engineer->user?->detail?->phone,
            ],
        ]);
    }

    private function formViewData(EngineerDetail $engineer, ?User $user): array
    {
        return [
            'engineer' => $engineer,
            'user' => $user,
            'userDetail' => $user?->detail ?? new UserDetail(),
            'organizations' => Organization::orderBy('name')->get(),
            'districts' => District::where('state_id', 15)->orderBy('name_en')->get(),
            'blocks' => BlockList::with('district')->orderBy('block_name_eng')->get(),
            'divisions' => Division::orderBy('name')->get(),
            'subDivisions' => SubDivision::orderBy('name')->get(),
            'postTypes' => PostType::orderBy('name')->get(),
            'departments' => Department::orderBy('name')->get(),
        ];
    }

    private function validatedData(Request $request, ?EngineerDetail $engineer = null): array
    {
        $userId = $engineer?->user_id;

        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)],
            'password' => [$engineer ? 'nullable' : 'required', 'string', 'min:8'],
            'current_organization_id' => ['required', 'exists:organizations,id'],
            'parent_organization_id' => ['nullable', 'exists:organizations,id'],
            'district_id' => [
                'required',
                Rule::exists('districts', 'id')->where(function ($query) {
                    $query->where('state_id', 15);
                }),
            ],
            'block_id' => ['required', 'exists:block_lists,id'],
            'division_id' => ['nullable', 'exists:divisions,id'],
            'sub_division_id' => ['nullable', 'exists:sub_divisions,id'],
            'post_type_id' => ['required', 'exists:post_types,id'],
            'department_id' => ['required', 'exists:departments,id'],
            'employee_name' => ['required', 'string', 'max:255'],
            'employee_hindi_name' => ['nullable', 'string', 'max:255'],
            'phone' => [$engineer ? 'sometimes' : 'nullable', 'nullable', 'string', 'max:20'],
            'state_government_engineer_id' => [
                $engineer ? 'sometimes' : 'required',
                'nullable',
                'string',
                'max:255',
                Rule::unique('engineer_details', 'state_government_engineer_id_hash')
                    ->ignore($engineer?->id)
                    ->whereNull('deleted_at'),
            ],
            'date_of_birth' => ['nullable', 'date'],
            'anniversary_date' => ['nullable', 'date'],
            'spouse_name' => ['nullable', 'string', 'max:255'],
            'no_of_children' => ['nullable', 'integer', 'min:0'],
            'aadhar_no' => ['nullable', 'string', 'max:20'],
            'pan_card_no' => ['nullable', 'string', 'max:20'],
        ]);

        $block = BlockList::query()->find($validated['block_id']);

        if ($block && (int) $block->district_id !== (int) $validated['district_id']) {
            throw ValidationException::withMessages([
                'block_id' => 'Selected block does not belong to the chosen district.',
            ]);
        }

        if (! empty($validated['sub_division_id'])) {
            $subDivision = SubDivision::query()->find($validated['sub_division_id']);

            if (! empty($validated['division_id']) && $subDivision && (int) $subDivision->division_id !== (int) $validated['division_id']) {
                throw ValidationException::withMessages([
                    'sub_division_id' => 'Selected sub division does not belong to the chosen division.',
                ]);
            }
        }

        if (empty($validated['division_id'])) {
            $validated['sub_division_id'] = null;
        }

        return $validated;
    }

    private function engineerPayload(array $validated, int $userId, ?EngineerDetail $engineer = null): array
    {
        return [
            'user_id' => $userId,
            'current_organization_id' => $validated['current_organization_id'],
            'parent_organization_id' => $validated['parent_organization_id'] ?? null,
            'district_id' => $validated['district_id'],
            'block_id' => $validated['block_id'],
            'division_id' => $validated['division_id'] ?? null,
            'sub_division_id' => $validated['sub_division_id'] ?? null,
            'post_type_id' => $validated['post_type_id'],
            'department_id' => $validated['department_id'],
            'employee_name' => $validated['employee_name'],
            'employee_hindi_name' => $validated['employee_hindi_name'] ?? null,
            'state_government_engineer_id' => $this->preserveSensitiveValue($validated, 'state_government_engineer_id', $engineer?->state_government_engineer_id),
            'date_of_birth' => $validated['date_of_birth'] ?? null,
            'anniversary_date' => $validated['anniversary_date'] ?? null,
            'spouse_name' => $validated['spouse_name'] ?? null,
            'no_of_children' => $validated['no_of_children'] ?? null,
            'aadhar_no' => $this->preserveSensitiveValue($validated, 'aadhar_no', $engineer?->aadhar_no),
            'pan_card_no' => $this->preserveSensitiveValue($validated, 'pan_card_no', $engineer?->pan_card_no),
        ];
    }

    private function preserveSensitiveValue(array $validated, string $key, ?string $existing): ?string
    {
        if (! array_key_exists($key, $validated)) {
            return $existing;
        }

        if ($validated[$key] === null || trim((string) $validated[$key]) === '') {
            return $existing;
        }

        return $validated[$key];
    }

    private function resolveEngineer(string $engineer): EngineerDetail
    {
        try {
            $engineerId = (int) Crypt::decryptString($engineer);
        } catch (\Throwable $exception) {
            abort(404);
        }

        return EngineerDetail::query()->findOrFail($engineerId);
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
