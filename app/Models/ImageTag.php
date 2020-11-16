<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImageTag extends Model
{
    protected $table = 'image_tag';

    protected $fillable = [
        'image_id',
        'tag_id',
        'ratio_width',
        'ratio_height',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'tag_id');
    }

    public function image()
    {
        return $this->belongsTo(Image::class, 'image_id');
    }
}
