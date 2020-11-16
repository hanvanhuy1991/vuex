<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $fillable = [
        'user_id',
        'likeable_type',
        'likeable_id',
        'is_liked',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function likeable()
    {
        return $this->morphTo();
    }
}
