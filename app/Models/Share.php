<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Share extends Model
{
    protected $fillable = [
        'user_id',
        'post_id',
        'share_date',
        'type',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
}
