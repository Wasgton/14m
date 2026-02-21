<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Artist extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(['*'])->logOnlyDirty()->dontSubmitEmptyLogs();
    }
    protected $fillable = [
        'name',
        'genre',
        'image_url',
        'instagram_url',
    ];

    public function events()
    {
        return $this->belongsToMany(Event::class)
            ->withPivot('cover_image', 'display_order')
            ->withTimestamps();
    }
}
