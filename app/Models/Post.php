<?php

namespace App\Models;

use App\Http\Traits\FullTextSearch;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;

class Post extends Model
{
    use SoftDeletes, FullTextSearch, Sluggable, SluggableScopeHelpers;

    protected $table = 'posts';

    protected $fillable = [
        'title',
        'content',
        'location_name',
        'location_lat',
        'location_long',
        'status',
        'view',
        'media_type',
        'thumbnail',
        'thumbnail_width',
        'thumbnail_height',
        'account_type',
        'point',
        'slug',
        'current_language',
        'action_time',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => ['title', 'user.name']
            ]
        ];
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function shares()
    {
        return $this->belongsToMany(
            User::class,
            'shares',
            'post_id',
            'user_id'
        )
            ->withPivot([
                'share_date',
                'type',
            ])->withTimestamps();
    }

    public function lastViews()
    {
        return $this->belongsToMany(
            User::class,
            'last_views',
            'post_id',
            'user_id'
        )
            ->withPivot([
                'last_view',
            ])->withTimestamps();
    }

    public function favorites()
    {
        return $this->belongsToMany(
            User::class,
            'favorites',
            'post_id',
            'user_id'
        )
            ->withPivot([
                'is_favorited',
            ])->withTimestamps();
    }

    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class, 'post_id');
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function videos()
    {
        return $this->morphMany(Video::class, 'videoable');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function mentions()
    {
        return $this->morphMany(Mention::class, 'mentionable');
    }

    public function reports()
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class, 'topic_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function draftPost()
    {
        return $this->hasOne(DraftPost::class);
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function hashTags()
    {
        return $this->belongsToMany(
            HashTag::class,
            'hash_tag_post',
            'post_id',
            'hash_tag_id'
        )->withPivot([
            'created_by',
            'updated_by',
            'deleted_by',
        ])->withTimestamps();
    }

    public function hiddens()
    {
        return $this->morphMany(Hidden::class, 'hiddenable');
    }

    public function lastActions()
    {
        return $this->hasMany(LastAction::class, 'post_id');
    }

    public function translates()
    {
        return $this->morphMany(Translate::class, 'translateable');
    }
}
