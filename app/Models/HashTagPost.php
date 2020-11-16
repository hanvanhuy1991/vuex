<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HashTagPost extends Model
{
    protected $table = 'hash_tag_post';

    protected $fillable = [
        'post_id',
        'hash_tag_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
}
