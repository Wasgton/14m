<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBannerRequest;
use App\Http\Requests\UpdateBannerRequest;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index()
    {
        return response()->json(Banner::orderBy('order')->get());
    }

    public function store(StoreBannerRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            $validated['image_url'] = '/storage/' . $request->file('image')->store('banners', 'public');
        }

        $banner = Banner::create($validated);

        return response()->json($banner, 201);
    }

    public function show(Banner $banner)
    {
        return response()->json($banner);
    }

    public function update(UpdateBannerRequest $request, Banner $banner)
    {
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            if ($banner->image_url && str_starts_with($banner->image_url, '/storage/')) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $banner->image_url));
            }
            $validated['image_url'] = '/storage/' . $request->file('image')->store('banners', 'public');
        }

        $banner->update($validated);

        return response()->json($banner);
    }

    public function destroy(Banner $banner)
    {
        if ($banner->image_url && str_starts_with($banner->image_url, '/storage/')) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $banner->image_url));
        }
        
        $banner->delete();

        return response()->json(null, 204);
    }
}
