<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HashTag extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'topic_id',
        'hash_tag_name',
        'point',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function topic()
    {
        return $this->belongsTo(Topic::class, 'topic_id');
    }

    public function posts()
    {
        return $this->belongsToMany(
            Post::class,
            'hash_tag_post',
            'hash_tag_id',
            'post_id'
        )->withPivot([
            'created_by',
            'updated_by',
            'deleted_by',
        ])->withTimestamps();
    }

    public function hashTagPosts()
    {
        return $this->hasMany(HashTagPost::class, 'hash_tag_id');
    }
}
