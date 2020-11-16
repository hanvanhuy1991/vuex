<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'tag_name',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function tagImage()
    {
        return $this->belongsToMany(
            Image::class,
            'image_tag',
            'tag_id',
            'image_id'
        )
            ->withPivot([])->withTimestamps();
    }
}
