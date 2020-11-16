<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Topic extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'image',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function posts()
    {
        return $this->hasMany(Post::class, 'topic_id');
    }

    public function hashTags()
    {
        return $this->hasMany(HashTag::class, 'topic_id');
    }
}
