<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        return response()->json(Event::with(['lineup', 'media'])->orderBy('date', 'desc')->get());
    }

    public function store(StoreEventRequest $request)
    {
        $validated = $request->validated();

        $event = Event::create($validated);

        if ($request->has('lineup') && is_array($request->input('lineup'))) {
            $event->lineup()->sync($request->input('lineup'));
        }

        return response()->json($event->load(['lineup', 'media']), 201);
    }

    public function show(Event $event)
    {
        return response()->json($event->load(['lineup', 'media']));
    }

    public function update(UpdateEventRequest $request, Event $event)
    {
        $validated = $request->validated();

        $event->update($validated);

        if ($request->has('lineup') && is_array($request->input('lineup'))) {
            $event->lineup()->sync($request->input('lineup'));
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
