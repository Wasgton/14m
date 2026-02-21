<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventMedia extends Model
{
    protected $fillable = [
        'event_id',
        'media_url',
        'type',
        'order',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
