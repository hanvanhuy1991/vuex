<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessCategory extends Model
{
    protected $fillable = [
        'id',
        'name',
        'status'
    ];

    public function userBusinessCategories()
    {
        return $this->hasMany(UserBusinessCategory::class, 'business_category_id');
    }
}
