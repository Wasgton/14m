<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PartnerController extends Controller
{
    public function index()
    {
        return response()->json(Partner::orderBy('order')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'required|image|max:5120',
            'is_active' => 'boolean',
            'order' => 'integer',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo_url'] = '/storage/' . $request->file('logo')->store('partners', 'public');
        }

        $partner = Partner::create($validated);

        return response()->json($partner, 201);
    }

    public function show(Partner $partner)
    {
        return response()->json($partner);
    }

    public function update(Request $request, Partner $partner)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'logo' => 'nullable|image|max:5120',
            'is_active' => 'boolean',
            'order' => 'integer',
        ]);

        if ($request->hasFile('logo')) {
            if ($partner->logo_url && str_starts_with($partner->logo_url, '/storage/')) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $partner->logo_url));
            }
            $validated['logo_url'] = '/storage/' . $request->file('logo')->store('partners', 'public');
        }

        $partner->update($validated);

        return response()->json($partner);
    }

    public function destroy(Partner $partner)
    {
        if ($partner->logo_url && str_starts_with($partner->logo_url, '/storage/')) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $partner->logo_url));
        }
        
        $partner->delete();

        return response()->json(null, 204);
    }
}
