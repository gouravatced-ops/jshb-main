<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\SubDivision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Throwable;

class SubDivisionController extends Controller
{
    public function index(Request $request)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        try {
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
        } catch (Throwable $e) {
            Log::error('SubDivision index failed: ' . $e->getMessage(), [
                'action' => 'sub_division.index',
                'exception' => $e,
            ]);

            return redirect()
                ->route('admin.dashboard')
                ->with('error', 'Something went wrong while loading sub divisions. Please try again.');
        }
    }

    public function search(Request $request)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        try {
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
        } catch (Throwable $e) {
            Log::error('SubDivision search failed: ' . $e->getMessage(), [
                'action' => 'sub_division.search',
                'exception' => $e,
            ]);

            return response()->json([
                'data' => [],
                'error' => 'Unable to fetch sub divisions.',
            ], 500);
        }
    }

    public function create()
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        try {
            $subDivision = new SubDivision([
                'status' => true,
            ]);
            $divisions = Division::where('status', true)->orderBy('name')->get();

            return view('admin.sub-division.add', compact('subDivision', 'divisions'));
        } catch (Throwable $e) {
            Log::error('SubDivision create page failed: ' . $e->getMessage(), [
                'action' => 'sub_division.create',
                'exception' => $e,
            ]);

            return redirect()
                ->route('admin.sub-divisions.index')
                ->with('error', 'Unable to load add sub division form. Please try again.');
        }
    }

    public function store(Request $request)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        try {
            $data = $this->validatedData($request);

            DB::connection('adms_jshb')->beginTransaction();
            SubDivision::create($data);
            DB::connection('adms_jshb')->commit();

            return redirect()
                ->route('admin.sub-divisions.index')
                ->with('success', 'Sub Division created successfully.');
        } catch (ValidationException $e) {
            throw $e;
        } catch (Throwable $e) {
            if (DB::connection('adms_jshb')->transactionLevel() > 0) {
                DB::connection('adms_jshb')->rollBack();
            }
            Log::error('SubDivision store failed: ' . $e->getMessage(), [
                'action' => 'sub_division.store',
                'input' => $request->except(['_token']),
                'exception' => $e,
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Something went wrong while processing your request. Please try again.');
        }
    }

    public function edit(SubDivision $subDivision)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        try {
            $divisions = Division::orderBy('name')->get();

            return view('admin.sub-division.edit', compact('subDivision', 'divisions'));
        } catch (Throwable $e) {
            Log::error('SubDivision edit page failed: ' . $e->getMessage(), [
                'action' => 'sub_division.edit',
                'sub_division_id' => $subDivision->id,
                'exception' => $e,
            ]);

            return redirect()
                ->route('admin.sub-divisions.index')
                ->with('error', 'Unable to load edit sub division form. Please try again.');
        }
    }

    public function update(Request $request, SubDivision $subDivision)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        try {
            $data = $this->validatedData($request, $subDivision);

            DB::connection('adms_jshb')->beginTransaction();
            $subDivision->update($data);
            DB::connection('adms_jshb')->commit();

            return redirect()
                ->route('admin.sub-divisions.index')
                ->with('success', 'Sub Division updated successfully.');
        } catch (ValidationException $e) {
            throw $e;
        } catch (Throwable $e) {
            if (DB::connection('adms_jshb')->transactionLevel() > 0) {
                DB::connection('adms_jshb')->rollBack();
            }
            Log::error('SubDivision update failed: ' . $e->getMessage(), [
                'action' => 'sub_division.update',
                'sub_division_id' => $subDivision->id,
                'input' => $request->except(['_token']),
                'exception' => $e,
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Something went wrong while processing your request. Please try again.');
        }
    }

    public function toggleStatus(SubDivision $subDivision)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        try {
            DB::connection('adms_jshb')->beginTransaction();
            $subDivision->update([
                'status' => !$subDivision->status,
            ]);
            DB::connection('adms_jshb')->commit();

            $statusText = $subDivision->status ? 'activated' : 'deactivated';

            return redirect()
                ->route('admin.sub-divisions.index')
                ->with('success', "Sub Division has been {$statusText} successfully.");
        } catch (Throwable $e) {
            if (DB::connection('adms_jshb')->transactionLevel() > 0) {
                DB::connection('adms_jshb')->rollBack();
            }
            Log::error('SubDivision toggle status failed: ' . $e->getMessage(), [
                'action' => 'sub_division.toggle_status',
                'sub_division_id' => $subDivision->id,
                'exception' => $e,
            ]);

            return redirect()
                ->route('admin.sub-divisions.index')
                ->with('error', 'Something went wrong while processing your request. Please try again.');
        }
    }

    public function destroy(SubDivision $subDivision)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        try {
            DB::connection('adms_jshb')->beginTransaction();

            if ($subDivision->allottees()->exists()) {
                DB::connection('adms_jshb')->rollBack();
                return redirect()
                    ->route('admin.sub-divisions.index')
                    ->with('error', 'Cannot delete this sub-division because active allottees are assigned to it.');
            }

            if ($subDivision->schemes()->exists()) {
                DB::connection('adms_jshb')->rollBack();
                return redirect()
                    ->route('admin.sub-divisions.index')
                    ->with('error', 'Cannot delete this sub-division because active schemes are linked to it.');
            }

            $subDivision->delete();
            DB::connection('adms_jshb')->commit();

            return redirect()
                ->route('admin.sub-divisions.index')
                ->with('success', 'Sub Division deleted successfully.');
        } catch (Throwable $e) {
            if (DB::connection('adms_jshb')->transactionLevel() > 0) {
                DB::connection('adms_jshb')->rollBack();
            }
            Log::error('SubDivision destroy failed: ' . $e->getMessage(), [
                'action' => 'sub_division.destroy',
                'sub_division_id' => $subDivision->id,
                'exception' => $e,
            ]);

            return redirect()
                ->route('admin.sub-divisions.index')
                ->with('error', 'Unable to delete sub-division. It may be referenced by other records.');
        }
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

        return null;
    }
}
