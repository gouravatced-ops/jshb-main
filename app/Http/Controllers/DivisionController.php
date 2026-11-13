<?php

namespace App\Http\Controllers;

use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Throwable;

class DivisionController extends Controller
{
    public function index(Request $request)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        try {
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
        } catch (Throwable $e) {
            Log::error('Division index failed: ' . $e->getMessage(), [
                'action' => 'division.index',
                'exception' => $e,
            ]);

            return redirect()
                ->route('admin.dashboard')
                ->with('error', 'Something went wrong while loading divisions. Please try again.');
        }
    }

    public function search(Request $request)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        try {
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
        } catch (Throwable $e) {
            Log::error('Division search failed: ' . $e->getMessage(), [
                'action' => 'division.search',
                'exception' => $e,
            ]);

            return response()->json([
                'data' => [],
                'error' => 'Unable to fetch divisions.',
            ], 500);
        }
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

        try {
            $data = $this->validatedData($request);

            DB::connection('adms_jshb')->beginTransaction();
            Division::create($data);
            DB::connection('adms_jshb')->commit();

            return redirect()
                ->route('admin.divisions.index')
                ->with('success', 'Division created successfully.');
        } catch (ValidationException $e) {
            throw $e;
        } catch (Throwable $e) {
            if (DB::connection('adms_jshb')->transactionLevel() > 0) {
                DB::connection('adms_jshb')->rollBack();
            }
            Log::error('Division store failed: ' . $e->getMessage(), [
                'action' => 'division.store',
                'input' => $request->except(['_token']),
                'exception' => $e,
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Something went wrong while processing your request. Please try again.');
        }
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

        try {
            $data = $this->validatedData($request, $division);

            DB::connection('adms_jshb')->beginTransaction();
            $division->update($data);
            DB::connection('adms_jshb')->commit();

            return redirect()
                ->route('admin.divisions.index')
                ->with('success', 'Division updated successfully.');
        } catch (ValidationException $e) {
            throw $e;
        } catch (Throwable $e) {
            if (DB::connection('adms_jshb')->transactionLevel() > 0) {
                DB::connection('adms_jshb')->rollBack();
            }
            Log::error('Division update failed: ' . $e->getMessage(), [
                'action' => 'division.update',
                'division_id' => $division->id,
                'input' => $request->except(['_token']),
                'exception' => $e,
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Something went wrong while processing your request. Please try again.');
        }
    }

    public function toggleStatus(Division $division)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        try {
            DB::connection('adms_jshb')->beginTransaction();
            $division->update([
                'status' => !$division->status,
            ]);
            DB::connection('adms_jshb')->commit();

            $statusText = $division->status ? 'activated' : 'deactivated';

            return redirect()
                ->route('admin.divisions.index')
                ->with('success', "Division has been {$statusText} successfully.");
        } catch (Throwable $e) {
            if (DB::connection('adms_jshb')->transactionLevel() > 0) {
                DB::connection('adms_jshb')->rollBack();
            }
            Log::error('Division toggle status failed: ' . $e->getMessage(), [
                'action' => 'division.toggle_status',
                'division_id' => $division->id,
                'exception' => $e,
            ]);

            return redirect()
                ->route('admin.divisions.index')
                ->with('error', 'Something went wrong while processing your request. Please try again.');
        }
    }

    public function destroy(Division $division)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        try {
            DB::connection('adms_jshb')->beginTransaction();

            if ($division->subDivisions()->exists()) {
                DB::connection('adms_jshb')->rollBack();
                return redirect()
                    ->route('admin.divisions.index')
                    ->with('error', 'Cannot delete this division because one or more active sub-divisions are linked to it.');
            }

            if ($division->allottees()->exists()) {
                DB::connection('adms_jshb')->rollBack();
                return redirect()
                    ->route('admin.divisions.index')
                    ->with('error', 'Cannot delete this division because active allottees are assigned to it.');
            }

            $division->delete();
            DB::connection('adms_jshb')->commit();

            return redirect()
                ->route('admin.divisions.index')
                ->with('success', 'Division deleted successfully.');
        } catch (Throwable $e) {
            if (DB::connection('adms_jshb')->transactionLevel() > 0) {
                DB::connection('adms_jshb')->rollBack();
            }
            Log::error('Division delete failed: ' . $e->getMessage(), [
                'action' => 'division.destroy',
                'division_id' => $division->id,
                'exception' => $e,
            ]);

            return redirect()
                ->route('admin.divisions.index')
                ->with('error', 'Unable to delete division. It may be referenced by other records.');
        }
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

        return null;
    }
}
