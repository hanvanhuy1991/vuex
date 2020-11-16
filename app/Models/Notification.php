<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    public $fillable = [
        'id',
        'type',
        'data',
        'notifiable_id',
        'notifiable_type',
        'read_at',
        'view_at',
        'created_at',
        'updated_at',
    ];
}
