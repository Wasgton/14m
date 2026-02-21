<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Banner;
use App\Models\Partner;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Routing\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $totalEvents = Event::count();
        $activeBanners = Banner::where('is_active', true)->count();
        $activePartners = Partner::where('is_active', true)->count();
        
        // Em um sistema real, os "Acessos na Semana" viriam do Google Analytics ou similar.
        // Simularemos um valor fixo.
        $weeklyAccess = "1.4k";

        // Recuperar as atividades recentes com os usuários (causers) e os modelos afetados (subjects)
        $activities = Activity::with('causer')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($activity) {
                $causer = $activity->causer ? $activity->causer->name : 'Sistema';
                $modelName = class_basename($activity->subject_type);
                $event = trans("events.{$activity->event}"); // created, updated, deleted
                
                // Mapeia eventos básicos (pode usar lang/pt-Br/events.php numa config real, mas faremos na tradução inline)
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
                
                // Pega o nome/título se existir no modelo
                $subjectName = "";
                if ($activity->subject) {
                   $subjectName = $activity->subject->name ?? $activity->subject->title ?? "#{$activity->subject->id}";
                } elseif (isset($activity->properties['old'])) {
                   // Para casos de 'deleted' onde o modelo não existe mais no banco
                   $oldProps = $activity->properties['old'];
                   $subjectName = $oldProps['name'] ?? $oldProps['title'] ?? "#" . ($oldProps['id'] ?? '?');
                }

                $message = "{$causer} {$eventTranslated} {$modelTranslated} \"{$subjectName}\"";

                return [
                    'id' => $activity->id,
                    'message' => trim($message),
                    'causer' => $causer,
                    'created_at_human' => $activity->created_at->diffForHumans(),
                ];
            });

        return response()->json([
            'stats' => [
                'total_events' => $totalEvents,
                'active_banners' => $activeBanners,
                'active_partners' => $activePartners,
                'weekly_access' => $weeklyAccess,
            ],
            'recent_activities' => $activities,
        ]);
    }
}
