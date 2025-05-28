<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FooterLink extends Model
{
    protected $fillable = [
        'name',
        'url',
        'order',
        'parent_id',
    ];

    public function parent()
    {
        return $this->belongsTo(FooterLink::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(FooterLink::class, 'parent_id')->orderBy('order');
    }
}
