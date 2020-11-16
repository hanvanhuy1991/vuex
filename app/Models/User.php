<?php

namespace App\Models;

use App\Enums\FavoriteEnum;
use App\Enums\HiddenEnum;
use App\Enums\LikeEnum;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable, HasRoles, HasApiTokens;

    protected $guard_name = 'api';

    protected $fillable = [
        'name',
        'email',
        'password',
        'country_code',
        'phone',
        'gender',
        'birthday',
        'type',
        'expiry_date',
        'url',
        'description',
        'status',
        'point',
        'avatar',
        'cover_image',
        'bio',
        'website',
        'address',
        'location_lat',
        'location_long',
        'location_name',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function shares()
    {
        return $this->belongsToMany(
            Post::class,
            'shares',
            'user_id',
            'post_id'
        )
            ->withPivot([
                'share_date',
                'type',
            ])->withTimestamps();
    }

    public function lastViews()
    {
        return $this->belongsToMany(
            Post::class,
            'last_views',
            'user_id',
            'post_id'
        )
            ->withPivot([
                'last_view',
            ])->withTimestamps();
    }

    public function favorites()
    {
        return $this->belongsToMany(
            Post::class,
            'favorites',
            'user_id',
            'post_id'
        )
            ->withPivot([
                'is_favorited',
            ])->withTimestamps();
    }

    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class, 'user_id');
    }

    public function likes()
    {
        return $this->hasMany(Like::class, 'user_id');
    }

    public function liked()
    {
        return $this->hasMany(Like::class, 'created_by')
            ->whereIsLiked(LikeEnum::STATUS_LIKE);
    }

    public function advertisementViews()
    {
        return $this->belongsToMany(
            Advertisement::class,
            'advertisement_views',
            'user_id',
            'advertisement_id'
        )
            ->withPivot([])->withTimestamps();
    }

    public function reports()
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    public function tokenDevices()
    {
        return $this->hasMany(TokenDevice::class, 'user_id');
    }

    public function pages()
    {
        return $this->hasMany(Page::class, 'user_id');
    }

    public function helpCenters()
    {
        return $this->hasMany(HelpCenter::class, 'user_id');
    }

    public function interests()
    {
        return $this->hasMany(Interest::class, 'user_id');
    }

    public function interestsTopic()
    {
        return $this->belongsToMany(
            Topic::class,
            'interests',
            'user_id',
            'interested_in'
        );
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'user_id');
    }

    public function searchHistories()
    {
        return $this->hasMany(SearchHistory::class, 'user_id');
    }

    public function paymentHistories()
    {
        return $this->hasMany(PaymentHistory::class, 'user_id');
    }

    public function followers()
    {
        return $this->hasMany(Follow::class, 'is_followed_id');
    }

    public function following()
    {
        return $this->hasMany(Follow::class, 'follower_id');
    }

    public function blockers()
    {
        return $this->hasMany(Block::class, 'blocker_id');
    }

    public function blocking()
    {
        return $this->hasMany(Block::class, 'is_blocked_id');
    }

    public function lastActions()
    {
        return $this->hasMany(LastAction::class);
    }

    public function lastSearch()
    {
        return $this->hasOne(SearchHistory::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'created_by');
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function collections()
    {
        return $this->hasMany(Collection::class, 'user_id');
    }

    public function hiddenPosts()
    {
        return $this->hasMany(Hidden::class, 'user_id')
            ->where('hiddenable_type', HiddenEnum::MODEL_POST);
    }

    public function bookmarkPosts()
    {
        return $this->belongsToMany(Post::class, Bookmark::class);
    }

    public function notificationSettings()
    {
        return $this->hasOne(NotificationSetting::class);
    }

    public function userBusinessCategories()
    {
        return $this->hasMany(UserBusinessCategory::class, 'user_id');
    }

    public function businessCategories()
    {
        return $this->belongsToMany(BusinessCategory::class, UserBusinessCategory::class);
    }

    public function phoneContacts()
    {
        return $this->hasMany(PhoneContact::class);
    }
}
