<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Translate extends Model
{
    protected $fillable = [
        'post_id',
        'language',
        'content'
    ];

    public function translateable()
    {
        return $this->morphTo();
    }
}
