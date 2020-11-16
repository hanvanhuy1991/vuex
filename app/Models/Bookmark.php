<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bookmark extends Model
{
    public $table = 'favorites';

    protected $fillable = [
        'user_id',
        'collection_id',
        'post_id',
        'is_favorited',
        'updated_at',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function collection()
    {
        return $this->belongsTo(Collection::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
