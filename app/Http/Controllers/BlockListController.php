<?php

namespace App\Http\Controllers;

use App\Models\BlockList;
use App\Models\District;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class BlockListController extends Controller
{
    public function index(Request $request)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $search = trim((string) $request->get('search', ''));
        $districtId = (int) $request->get('district_id', 0);
        $districts = $this->jharkhandDistricts();

        $blocks = BlockList::with('district')
            ->whereHas('district', function ($query) {
                $query->where('state_id', 15);
            })
            ->when($districtId > 0, function ($query) use ($districtId) {
                $query->where('district_id', $districtId);
            })
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('block_name_eng', 'like', '%' . $search . '%')
                        ->orWhere('block_name_hn', 'like', '%' . $search . '%')
                        ->orWhereHas('district', function ($districtQuery) use ($search) {
                            $districtQuery->where('name_en', 'like', '%' . $search . '%');
                        });
                });
            })
            ->orderBy('district_id')
            ->orderBy('block_name_eng')
            ->paginate(50)
            ->withQueryString();

        return view('admin.block.index', compact('blocks', 'search', 'districts', 'districtId'));
    }

    public function search(Request $request)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $search = trim((string) $request->get('search', ''));
        $districtId = (int) $request->get('district_id', 0);

        $blocks = BlockList::with('district')
            ->whereHas('district', function ($query) {
                $query->where('state_id', 15);
            })
            ->when($districtId > 0, function ($query) use ($districtId) {
                $query->where('district_id', $districtId);
            })
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('block_name_eng', 'like', '%' . $search . '%')
                        ->orWhere('block_name_hn', 'like', '%' . $search . '%')
                        ->orWhereHas('district', function ($districtQuery) use ($search) {
                            $districtQuery->where('name_en', 'like', '%' . $search . '%');
                        });
                });
            })
            ->orderBy('district_id')
            ->orderBy('block_name_eng')
            ->get()
            ->map(function (BlockList $block) {
                return [
                    'id' => $block->id,
                    'district_name' => $block->district?->name_en ?: '-',
                    'block_name_eng' => $block->block_name_eng,
                    'block_name_hn' => $block->block_name_hn ?: '-',
                    'status' => $block->status,
                    'status_label' => $block->status ? 'Active' : 'Inactive',
                    'edit_url' => route('admin.blocks.edit', $block),
                    'delete_url' => route('admin.blocks.destroy', $block),
                ];
            })
            ->values();

        return response()->json([
            'data' => $blocks,
        ]);
    }

    public function create()
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $block = new BlockList([
            'status' => true,
        ]);
        $districts = $this->jharkhandDistricts();

        return view('admin.block.add', compact('block', 'districts'));
    }

    public function store(Request $request)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $data = $this->validatedData($request);
        BlockList::create($data);

        return redirect()
            ->route('admin.blocks.index')
            ->with('success', 'Block created successfully.');
    }

    public function edit(BlockList $block)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $districts = $this->jharkhandDistricts();

        return view('admin.block.edit', compact('block', 'districts'));
    }

    public function update(Request $request, BlockList $block)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $data = $this->validatedData($request, $block);
        $block->update($data);

        return redirect()
            ->route('admin.blocks.index')
            ->with('success', 'Block updated successfully.');
    }

    public function destroy(BlockList $block)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        $block->delete();

        return redirect()
            ->route('admin.blocks.index')
            ->with('success', 'Block deleted successfully.');
    }

    private function validatedData(Request $request, ?BlockList $block = null): array
    {
        $validated = $request->validate([
            'district_id' => [
                'required',
                Rule::exists('districts', 'id')->where(function ($query) {
                    $query->where('state_id', 15);
                }),
            ],
            'block_name_eng' => [
                'required',
                'string',
                'max:255',
                Rule::unique('block_lists', 'block_name_eng')
                    ->ignore($block?->id)
                    ->whereNull('deleted_at'),
            ],
            'block_name_hn' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'boolean'],
        ]);

        $validated['status'] = $request->boolean('status');

        return $validated;
    }

    private function jharkhandDistricts()
    {
        return District::query()
            ->where('state_id', 15)
            ->orderBy('name_en')
            ->get();
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
