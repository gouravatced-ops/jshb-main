<?php

namespace App\Http\Controllers;

use App\Models\PostType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PostTypeController extends Controller
{
    public function index(Request $request)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $search = trim((string) $request->get('search', ''));

        $postTypes = PostType::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('level', 'like', '%' . $search . '%')
                        ->orWhere('name', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('level')
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('admin.post-type.index', compact('postTypes', 'search'));
    }

    public function search(Request $request)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $search = trim((string) $request->get('search', ''));

        $postTypes = PostType::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('level', 'like', '%' . $search . '%')
                        ->orWhere('name', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('level')
            ->orderBy('name')
            ->get()
            ->map(function (PostType $postType) {
                return [
                    'id' => $postType->id,
                    'level' => $postType->level,
                    'name' => $postType->name,
                    'status' => $postType->status,
                    'status_label' => $postType->status ? 'Active' : 'Inactive',
                    'edit_url' => route('admin.post-types.edit', $postType),
                    'delete_url' => route('admin.post-types.destroy', $postType),
                ];
            })
            ->values();

        return response()->json([
            'data' => $postTypes,
        ]);
    }

    public function create()
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $postType = new PostType([
            'status' => true,
        ]);

        return view('admin.post-type.add', compact('postType'));
    }

    public function store(Request $request)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $data = $this->validatedData($request);
        PostType::create($data);

        return redirect()
            ->route('admin.post-types.index')
            ->with('success', 'Post Type created successfully.');
    }

    public function edit(PostType $postType)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        return view('admin.post-type.edit', compact('postType'));
    }

    public function update(Request $request, PostType $postType)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $data = $this->validatedData($request, $postType);
        $postType->update($data);

        return redirect()
            ->route('admin.post-types.index')
            ->with('success', 'Post Type updated successfully.');
    }

    public function destroy(PostType $postType)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $postType->delete();

        return redirect()
            ->route('admin.post-types.index')
            ->with('success', 'Post Type deleted successfully.');
    }

    private function validatedData(Request $request, ?PostType $postType = null): array
    {
        $validated = $request->validate([
            'level' => ['required', 'string', 'max:255'],
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('post_types', 'name')
                    ->ignore($postType?->id)
                    ->whereNull('deleted_at'),
            ],
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
