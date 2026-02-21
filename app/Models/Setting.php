<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value'];

    public static function getTitle()
    {
        return self::where('key', 'project_name')
            ->firstOrFail()
            ->value;
    }

    public function getLogo()
    {
        return self::where('key', 'logo')
            ->firstOrFail()
            ->value;
    }

    public function getContacts()
    {
        return self::where('key','like', 'contact_%')
            ->get();
    }
}
