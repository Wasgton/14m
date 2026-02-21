<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::with(['lineup', 'media']);
        
        if ($request->has('all')) {
            $query->orderBy('date', 'desc');
        } elseif ($request->has('past')) {
            $query->where('status', 'past')->orderBy('date', 'desc');
        } else {
            $query->where('date', '>=', now())
                  ->where('status', '<>', 'past')
                  ->orderBy('date', 'asc');
        }

        return response()->json($query->get());
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

        if ($request->exists('cover_media_id')) {
            $coverId = $request->input('cover_media_id');
            $event->media()->update(['cover_image' => false]);
            if ($coverId) {
                $event->media()->where('id', $coverId)->update(['cover_image' => true]);
            }
        }

        return response()->json($event->load(['lineup', 'media']));
    }

    public function destroy(Event $event)
    {
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
