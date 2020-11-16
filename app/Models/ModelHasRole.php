<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModelHasRole extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'role_id',
        'model_type',
        'model_id'
    ];
}
