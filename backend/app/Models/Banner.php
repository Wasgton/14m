<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Banner extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(['*'])->logOnlyDirty()->dontSubmitEmptyLogs();
    }
    protected $fillable = [
        'title',
        'subtitle',
        'image_url',
        'button_text',
        'button_link',
        'is_active',
        'order',
    ];
}
