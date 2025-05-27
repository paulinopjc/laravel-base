<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteLogo extends Model
{
    protected $fillable = [
        'type',
        'path',
        'alt_text',
    ];

    public function getUrlAttribute(): string
    {
        return asset("storage/logos/{$this->path}");
    }
}
