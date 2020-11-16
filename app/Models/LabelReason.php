<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabelReason extends Model
{
    protected $fillable = [
        'content',
        'type'
    ];

    public function reasons()
    {
        return $this->hasMany(Reason::class);
    }
}
