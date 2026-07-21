<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Workflow;
use App\Models\WorkflowStep;
use App\Models\Role;
use App\Models\DocumentMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class WorkflowController extends Controller
{
    public function index(Request $request)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $search = trim((string) $request->get('search', ''));

        $workflows = $this->filteredQuery($search)
            ->with(['steps' => function($q) {
                $q->orderBy('step_order', 'asc');
            }, 'steps.role'])
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.workflows.index', compact('workflows', 'search'));
    }

    public function search(Request $request)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $search = trim((string) $request->get('search', ''));

        $workflows = $this->filteredQuery($search)
            ->with(['steps' => function($q) {
                $q->orderBy('step_order', 'asc');
            }, 'steps.role'])
            ->orderByDesc('id')
            ->get()
            ->map(function (Workflow $workflow) {
                return [
                    'id' => $workflow->id,
                    'name' => $workflow->name,
                    'application_type' => $workflow->application_type,
                    'status' => $workflow->is_active,
                    'status_label' => $workflow->is_active ? 'Active' : 'Inactive',
                    'edit_url' => route('admin.workflows.edit', $workflow),
                    'delete_url' => route('admin.workflows.destroy', $workflow),
                    'toggle_status_url' => route('admin.workflows.toggle-status', $workflow),
                    'steps' => $workflow->steps->map(function($step) {
                        return [
                            'step_order' => $step->step_order,
                            'step_name' => $step->step_name,
                            'role_name' => $step->role ? $step->role->name : 'N/A',
                            'can_forward' => $step->can_forward,
                            'can_send_back' => $step->can_send_back,
                            'can_reject' => $step->can_reject,
                        ];
                    })->toArray()
                ];
            })
            ->values();

        return response()->json([
            'data' => $workflows,
        ]);
    }

    public function create()
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $roles = Role::orderBy('name')->get();
        $documents = DocumentMaster::orderBy('document_name')->get();
        $workflow = new Workflow([
            'is_active' => 1,
        ]);

        return view('admin.workflows.add', compact('roles', 'workflow', 'documents'));
    }

    public function store(Request $request)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $data = $this->validatedData($request);

        DB::transaction(function () use ($data, $request) {
            $workflow = Workflow::create([
                'name' => $data['name'],
                'slug' => $data['slug'],
                'application_type' => $data['application_type'],
                'description' => $data['description'] ?? null,
                'is_active' => $data['is_active'] ?? 1,
            ]);

            if (isset($data['steps']) && is_array($data['steps'])) {
                foreach ($data['steps'] as $index => $step) {
                    $workflow->steps()->create([
                        'step_order' => $step['step_order'],
                        'step_name' => $step['step_name'],
                        'step_code' => $step['step_code'],
                        'role_id' => $step['role_id'],
                        'action_type' => $step['action_type'],
                        'can_forward' => $step['can_forward'] ?? 0,
                        'can_reject' => $step['can_reject'] ?? 0,
                        'can_send_back' => $step['can_send_back'] ?? 0,
                        'can_upload_document' => $step['can_upload_document'] ?? 0,
                        'can_add_note' => $step['can_add_note'] ?? 0,
                        'requires_signature' => $step['requires_signature'] ?? 0,
                        'is_starting_step' => $step['is_starting_step'] ?? 0,
                        'is_final_step' => $step['is_final_step'] ?? 0,
                        'notification_template' => $step['notification_template'] ?? null,
                    ]);
                }
            }

            if (isset($data['required_documents']) && is_array($data['required_documents'])) {
                $workflow->requiredDocuments()->sync($data['required_documents']);
            }
        });

        return redirect()
            ->route('admin.workflows.index')
            ->with('success', 'Workflow created successfully.');
    }

    public function edit(Workflow $workflow)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $roles = Role::orderBy('name')->get();
        $documents = DocumentMaster::orderBy('document_name')->get();
        $workflow->load('steps', 'requiredDocuments');

        return view('admin.workflows.edit', compact('workflow', 'roles', 'documents'));
    }

    public function update(Request $request, Workflow $workflow)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $data = $this->validatedData($request, $workflow);

        DB::transaction(function () use ($data, $workflow, $request) {
            $workflow->update([
                'name' => $data['name'],
                'slug' => $data['slug'],
                'application_type' => $data['application_type'],
                'description' => $data['description'] ?? null,
                'is_active' => $data['is_active'] ?? 1,
            ]);

            $existingStepIds = [];

            if (isset($data['steps']) && is_array($data['steps'])) {
                foreach ($data['steps'] as $step) {
                    if (isset($step['id']) && $step['id']) {
                        // Update existing
                        $workflowStep = WorkflowStep::find($step['id']);
                        if ($workflowStep) {
                            $workflowStep->update([
                                'step_order' => $step['step_order'],
                                'step_name' => $step['step_name'],
                                'step_code' => $step['step_code'],
                                'role_id' => $step['role_id'],
                                'action_type' => $step['action_type'],
                                'can_forward' => $step['can_forward'] ?? 0,
                                'can_reject' => $step['can_reject'] ?? 0,
                                'can_send_back' => $step['can_send_back'] ?? 0,
                                'can_upload_document' => $step['can_upload_document'] ?? 0,
                                'can_add_note' => $step['can_add_note'] ?? 0,
                                'requires_signature' => $step['requires_signature'] ?? 0,
                                'is_starting_step' => $step['is_starting_step'] ?? 0,
                                'is_final_step' => $step['is_final_step'] ?? 0,
                                'notification_template' => $step['notification_template'] ?? null,
                            ]);
                            $existingStepIds[] = $workflowStep->id;
                        }
                    } else {
                        // Create new
                        $newStep = $workflow->steps()->create([
                            'step_order' => $step['step_order'],
                            'step_name' => $step['step_name'],
                            'step_code' => $step['step_code'],
                            'role_id' => $step['role_id'],
                            'action_type' => $step['action_type'],
                            'can_forward' => $step['can_forward'] ?? 0,
                            'can_reject' => $step['can_reject'] ?? 0,
                            'can_send_back' => $step['can_send_back'] ?? 0,
                            'can_upload_document' => $step['can_upload_document'] ?? 0,
                            'can_add_note' => $step['can_add_note'] ?? 0,
                            'requires_signature' => $step['requires_signature'] ?? 0,
                            'is_starting_step' => $step['is_starting_step'] ?? 0,
                            'is_final_step' => $step['is_final_step'] ?? 0,
                            'notification_template' => $step['notification_template'] ?? null,
                        ]);
                        $existingStepIds[] = $newStep->id;
                    }
                }
            }

            // Delete removed steps
            $workflow->steps()->whereNotIn('id', $existingStepIds)->delete();
            
            if (isset($data['required_documents']) && is_array($data['required_documents'])) {
                $workflow->requiredDocuments()->sync($data['required_documents']);
            } else {
                $workflow->requiredDocuments()->sync([]);
            }
        });

        return redirect()
            ->route('admin.workflows.index')
            ->with('success', 'Workflow updated successfully.');
    }

    public function toggleStatus(Workflow $workflow)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $workflow->update([
            'is_active' => !$workflow->is_active,
        ]);

        $message = $workflow->is_active ? 'Active' : 'Inactive';

        return redirect()
            ->route('admin.workflows.index')
            ->with('success', 'Workflow marked ' . $message . ' successfully.');
    }

    public function destroy(Workflow $workflow)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $workflow->steps()->delete();
        $workflow->delete();

        return redirect()
            ->route('admin.workflows.index')
            ->with('success', 'Workflow deleted successfully.');
    }

    private function filteredQuery(string $search)
    {
        return Workflow::when($search !== '', function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                  ->orWhere('application_type', 'like', '%' . $search . '%');
        });
    }

    private function validatedData(Request $request, ?Workflow $workflow = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('workflows', 'slug')->ignore($workflow?->id),
            ],
            'application_type' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
            'steps' => ['nullable', 'array'],
            'steps.*.id' => ['nullable', 'integer'],
            'steps.*.step_order' => ['required', 'integer', 'min:1'],
            'steps.*.step_name' => ['required', 'string', 'max:255'],
            'steps.*.step_code' => ['required', 'string', 'max:100'],
            'steps.*.role_id' => ['required', 'integer', 'exists:roles,id'],
            'steps.*.action_type' => ['required', 'string', 'max:100'],
            'steps.*.can_forward' => ['nullable', 'boolean'],
            'steps.*.can_reject' => ['nullable', 'boolean'],
            'steps.*.can_send_back' => ['nullable', 'boolean'],
            'steps.*.can_upload_document' => ['nullable', 'boolean'],
            'steps.*.can_add_note' => ['nullable', 'boolean'],
            'steps.*.requires_signature' => ['nullable', 'boolean'],
            'steps.*.is_starting_step' => ['nullable', 'boolean'],
            'steps.*.is_final_step' => ['nullable', 'boolean'],
            'steps.*.notification_template' => ['nullable', 'string'],
            'required_documents' => ['nullable', 'array'],
            'required_documents.*' => ['integer'],
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
