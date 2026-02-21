<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Event extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(['*'])->logOnlyDirty()->dontSubmitEmptyLogs();
    }
    protected $fillable = [
        'name',
        'date',
        'location',
        'description',
        'price',
        'status',
        'ticket_link',
    ];

    protected $casts = [
        'date' => 'datetime',
    ];

    public function artists()
    {
        return $this->belongsToMany(Artist::class)
            ->withPivot('cover_image', 'display_order')
            ->withTimestamps();
    }

    public function lineUp()
    {
        return $this->belongsToMany(Artist::class, 'event_artist', 'event_id', 'artist_id')
            ->withPivot('cover_image', 'display_order')
            ->withTimestamps();
    }
    
    public function media()
    {
        return $this->hasMany(EventMedia::class)->orderBy('order');
    }
}
