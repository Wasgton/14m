<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArtistController extends Controller
{
    public function index()
    {
        return response()->json(Artist::orderBy('name')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'genre' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:5120',
            'instagram_url' => 'nullable|url|max:255',
        ]);

        if ($request->hasFile('image')) {
            $validated['image_url'] = '/storage/' . $request->file('image')->store('artists', 'public');
        }

        $artist = Artist::create($validated);

        return response()->json($artist, 201);
    }

    public function show(Artist $artist)
    {
        return response()->json($artist);
    }

    public function update(Request $request, Artist $artist)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'genre' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:5120',
            'instagram_url' => 'nullable|url|max:255',
        ]);

        if ($request->hasFile('image')) {
            if ($artist->image_url && str_starts_with($artist->image_url, '/storage/')) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $artist->image_url));
            }
            $validated['image_url'] = '/storage/' . $request->file('image')->store('artists', 'public');
        }

        $artist->update($validated);

        return response()->json($artist);
    }

    public function destroy(Artist $artist)
    {
        if ($artist->image_url && str_starts_with($artist->image_url, '/storage/')) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $artist->image_url));
        }
        
        $artist->delete();

        return response()->json(null, 204);
    }
}
