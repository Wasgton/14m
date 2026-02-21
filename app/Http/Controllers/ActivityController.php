<?php

namespace App\Http\Controllers;

use Spatie\Activitylog\Models\Activity;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $activities = Activity::with('causer')
            ->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 15));

        $activities->getCollection()->transform(function ($activity) {
            $causer = $activity->causer ? $activity->causer->name : 'Sistema';
            $modelName = class_basename($activity->subject_type);
            
            $eventTranslated = match($activity->event) {
                'created' => 'criou',
                'updated' => 'atualizou',
                'deleted' => 'excluiu',
                default => $activity->event,
            };

            $modelTranslated = match($modelName) {
                'Event' => 'o evento',
                'Banner' => 'o banner',
                'Partner' => 'o parceiro',
                'Artist' => 'o artista',
                'User' => 'o usuário',
                'Role' => 'o cargo',
                default => "o registro $modelName",
            };
            
            $subjectName = "";
            if ($activity->subject) {
               $subjectName = $activity->subject->name ?? $activity->subject->title ?? "#{$activity->subject->id}";
            } elseif (isset($activity->properties['old'])) {
               $oldProps = $activity->properties['old'];
               $subjectName = $oldProps['name'] ?? $oldProps['title'] ?? "#" . ($oldProps['id'] ?? '?');
            }

            $message = "{$causer} {$eventTranslated} {$modelTranslated} \"{$subjectName}\"";

            return [
                'id' => $activity->id,
                'message' => trim($message),
                'causer' => $causer,
                'created_at_human' => $activity->created_at->diffForHumans(),
                'created_at' => $activity->created_at->format('d/m/Y H:i:s'),
                'properties' => $activity->properties,
            ];
        });

        return response()->json($activities);
    }
}
