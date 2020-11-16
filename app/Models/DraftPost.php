<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DraftPost extends Model
{
    protected $fillable = [
        'post_id',
        'time_public'
    ];

    public function post()
    {
        return $this->hasOne(Post::class);
    }
}
