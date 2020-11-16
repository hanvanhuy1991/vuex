<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserBusinessCategory extends Model
{
    protected $fillable = [
        'user_id',
        'business_category_id'
    ];
}
