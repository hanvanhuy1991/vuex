<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LastAction extends Model
{
    protected $fillable = [
        'user_id',
        'post_id',
        'type',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
