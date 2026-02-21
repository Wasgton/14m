<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        return response()->json(Event::with(['lineup', 'media'])->orderBy('date', 'desc')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'location' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'nullable|numeric|min:0',
            'status' => 'required|in:upcoming,sold-out,past',
            'ticket_link' => 'nullable|url|max:255',
        ]);

        $event = Event::create($validated);

        if ($request->has('lineup')) {
            $lineupData = [];
            foreach ($request->input('lineup') as $index => $artistId) {
                $lineupData[$artistId] = ['display_order' => $index];
            }
            $event->lineup()->sync($lineupData);
        }

        return response()->json($event->load(['lineup', 'media']), 201);
    }

    public function show(Event $event)
    {
        return response()->json($event->load(['lineup', 'media']));
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'date' => 'sometimes|required|date',
            'location' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'price' => 'nullable|numeric|min:0',
            'status' => 'sometimes|required|in:upcoming,sold-out,past',
            'ticket_link' => 'nullable|url|max:255',
        ]);

        $event->update($validated);

        if ($request->has('lineup')) {
            $lineupData = [];
            foreach ($request->input('lineup') as $index => $artistId) {
                $lineupData[$artistId] = ['display_order' => $index];
            }
            $event->lineup()->sync($lineupData);
        }

        return response()->json($event->load(['lineup', 'media']));
    }

    public function destroy(Event $event)
    {
        // Media deletion is handled via cascading or separate endpoint depending on setup,
        // Assuming the media files need physical deletion too:
        foreach ($event->media as $media) {
            if ($media->media_url && str_starts_with($media->media_url, '/storage/')) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete(str_replace('/storage/', '', $media->media_url));
            }
            $media->delete();
        }

        $event->delete();

        return response()->json(null, 204);
    }
}
