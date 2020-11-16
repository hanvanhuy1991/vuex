<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Collection extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'type'
    ];

    public function posts()
    {
        return $this->belongsToMany(
            Post::class,
            'favorites',
            'collection_id',
            'post_id'
        );
    }

    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }
}
