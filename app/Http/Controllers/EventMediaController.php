<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\EventMedia;
use Illuminate\Support\Facades\Storage;

class EventMediaController extends Controller
{
    public function store(Request $request, Event $event)
    {
        $request->validate([
            'media' => 'required|file|max:102400', // 100MB
            'cover_image' => 'nullable|boolean',
            'type' => 'nullable|in:photo,video',
            'order' => 'nullable|integer',
        ]);

        $path = $request->file('media')->store('events/media', 'public');
        $mediaUrl = '/storage/' . $path;

        $coverImage = filter_var($request->input('cover_image', false), FILTER_VALIDATE_BOOLEAN);

        if ($coverImage) {
            $event->media()->update(['cover_image' => false]);
        }

        $media = $event->media()->create([
            'media_url' => $mediaUrl,
            'cover_image' => $coverImage,
            'type' => $request->input('type', 'photo'),
            'order' => $request->input('order', 0),
        ]);

        return response()->json($media, 201);
    }

    public function destroy(Event $event, EventMedia $media)
    {
        if ($media->event_id !== $event->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($media->media_url && str_starts_with($media->media_url, '/storage/')) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $media->media_url));
        }

        $media->delete();

        return response()->json(null, 204);
    }
}
