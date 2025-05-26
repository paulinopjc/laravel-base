<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CmsPage extends Model
{
    protected $fillable = [
        'title', 
        'slug', 
        'meta_title',
        'meta_description',
        'meta_keywords',
        'content', 
        'is_published'
    ];
}
