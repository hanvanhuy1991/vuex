<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = [
        'user_id',
        'imageable_id',
        'imageable_type',
        'image_path',
        'width',
        'height',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $hidden = [
        'imageable_type',
        'imageable_id'
    ];

    public function imageable()
    {
        return $this->morphTo();
    }

    public function imageTags()
    {
        return $this->hasMany(ImageTag::class, 'image_id');
    }
}
