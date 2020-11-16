<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhoneContact extends Model
{
    protected $fillable = [
        'user_id',
        'phone_number'
    ];
}
