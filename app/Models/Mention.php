<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mention extends Model
{

    protected $fillable = [
        'user_id',
        'mentionable_id',
        'mentionable_type',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function mentionable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
