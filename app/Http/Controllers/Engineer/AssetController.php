<?php

namespace App\Http\Controllers\Engineer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EngineerAsset;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AssetController extends Controller
{
    public function index()
    {
        $assets = EngineerAsset::where('user_id', auth()->id())->get();
        return view('engineer.assets.index', compact('assets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'asset_type' => 'required|in:signature,stamp,other',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048' // 2MB max
        ]);

        $file = $request->file('image');
        $originalName = $file->getClientOriginalName();
        $filename = 'engineer_' . auth()->id() . '_' . time() . '_' . Str::random(10) . '.' . $file->extension();
        
        $path = $file->storeAs('engineer_assets', $filename, 'public');

        EngineerAsset::create([
            'user_id' => auth()->id(),
            'asset_type' => $request->asset_type,
            'file_path' => 'storage/' . $path,
            'original_name' => $originalName,
        ]);

        return redirect()->back()->with('success', 'Asset uploaded successfully.');
    }

    public function destroy($id)
    {
        $asset = EngineerAsset::where('user_id', auth()->id())->findOrFail($id);
        
        $relativePath = str_replace('storage/', '', $asset->file_path);
        if (Storage::disk('public')->exists($relativePath)) {
            Storage::disk('public')->delete($relativePath);
        }

        $asset->delete();
        
        return redirect()->back()->with('success', 'Asset deleted successfully.');
    }

    public function getAssetsForEditor()
    {
        $assets = EngineerAsset::where('user_id', auth()->id())->get();
        // Return full URLs
        $assets->transform(function ($asset) {
            $asset->full_url = asset($asset->file_path);
            return $asset;
        });
        
        return response()->json($assets);
    }
}
