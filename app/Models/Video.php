<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Video extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'videoable_id',
        'videoable_type',
        'video_path',
        'width',
        'height',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $hidden = [
        'videoable_type',
        'videoable_id'
    ];

    public function videoable()
    {
        return $this->morphTo();
    }
}
