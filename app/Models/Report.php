<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'user_id',
        'reportable_id',
        'reportable_type',
        'reason',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function reportable()
    {
        return $this->morphTo();
    }
}
