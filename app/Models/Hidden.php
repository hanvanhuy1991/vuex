<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hidden extends Model
{
    protected $fillable = [
        'user_id',
        'hiddenable_type',
        'hiddenable_id'
    ];

    public function hiddenable()
    {
        return $this->morphTo();
    }
}
