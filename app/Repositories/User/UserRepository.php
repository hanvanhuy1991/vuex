<?php

namespace App\Repositories\User;

use App\Enums\BlockEnum;
use App\Enums\FollowEnum;
use App\Models\User;
use App\Enums\UserRole;
use App\Repositories\BaseRepository;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Facades\Auth;

/**
 * Class UserRepository
 *
 * @package App\Repositories\User
 */
class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    /**
     * @var User
     */
    protected $model;

    /**
     * @var DatabaseManager
     */
    private $db;

    /**
     * UserRepository constructor.
     *
     * @param User $model
     * @param DatabaseManager $db
     */
    public function __construct(User $model, DatabaseManager $db)
    {
        parent::__construct($model);

        $this->db = $db;
    }

    public function getUserByRole($role)
    {
        return $this->model->role($role);
    }

    public function getOrderByToPost()
    {
        $query = $this->model->select('point');

        return $query->whereColumn('posts.created_by', 'users.id');
    }

    public function getProfile($user)
    {
        return $user->loadCount(['posts', 'followers', 'liked']);
    }

    /**
     * Get by name
     *
     * @param $keyword
     *
     * @return builder
     */
    public function getByName($keyword)
    {
        return $this->model->whereName($keyword);
    }

    /**
     * Get by search like
     *
     * @param array $keywords
     *
     * @return builder
     */
    public function getBySearchLike($keywords)
    {
        return $this->model->where(function ($q) use ($keywords) {
            foreach ($keywords as $keyword) {
                $like = '%'.$keyword.'%';
                $q->orWhere('name', 'like', $like);
            }
        });
    }

    /**
     * Query get data by ids
     *
     * @param $ids
     * @param $removeBlock
     *
     * @return builder
     */
    public function getByIds($ids, $removeBlock = true)
    {
        $userInvalidIds = Auth::user()->blockers()
            ->whereStatus(BlockEnum::STATUS_BLOCK)
            ->pluck('is_blocked_id')
            ->toArray();

        $validIds = $removeBlock ? array_diff($ids, $userInvalidIds) : $ids;

        return $this->model->whereIn('id', $validIds);
    }

    /**
     * Query get business account by ids
     *
     * @param $ids
     *
     * @return builder
     */
    public function getBusinessAccountByIds($ids)
    {
        return $this->getByIds($ids)->role(UserRole::BUSINESS_ACCOUNT);
    }

    /**
     * Get suggestion user
     *
     * @param $lat
     * @param $long
     * @param $validIds
     * @param $inValidIds
     * @param $phones
     *
     * @return mixed
     */
    public function suggestion($lat, $long, $validIds, $inValidIds, $phones)
    {
        return $this->model->whereNotIn('id', $inValidIds)
            ->where(function ($user) use ($phones, $lat, $long, $validIds) {
                $user->whereIn('phone', $phones)
                    ->orWhere(function ($q) use ($lat, $long) {
                        $q->whereBetween('location_lat', [($lat - UserRole::RADIUS_LOCATION), ($lat + UserRole::RADIUS_LOCATION)])
                            ->whereBetween('location_long', [($long - UserRole::RADIUS_LOCATION), ($long + UserRole::RADIUS_LOCATION)]);
                    })
                    ->orWhereIn('id', $validIds);
            })
            ->withCount('followers')
            ->orderBy('point', 'DESC');
    }

    public function getProfileAdmin($userId)
    {
        return $this->model->where('id', $userId)->with(['roles', 'roles.permissions'])->first();
    }

    // CMS FUNCTIONS

    public function getAccountForSearch()
    {
        return $this->model->whereHas('roles', function ($q) {
            $q->where('name', '<>', 'admin');
        })->get();
    }

    // Account management
    /**
     * Query get accounts difference admin role
     *
     * @param $request
     *
     * @return builder
     */
    public function listAccountCms($request)
    {
        $query = $this->model->whereHas('roles', function ($q) {
            $q->where('name', '<>', 'admin');
        })->with(['roles', 'roles.permissions', 'followers', 'following', 'businessCategories']);

        if (isset($request['status']) && $request['status'] != '') {
            $query = $query->whereStatus($request['status']);
        }

        if (isset($request['phone']) && $request['phone'] != '') {
            $query = $query->wherePhone($request['phone']);
        }

        if (isset($request['email']) && $request['email'] != '') {
            $query = $query->whereEmail($request['email']);
        }

        if (isset($request['type']) && $request['type'] != '') {
            $type = $request['type'];
            $query = $query->whereHas('roles', function ($q) use ($type) {
                $q->whereName($type);
            });
        }

        if (isset($request['category_id']) && $request['category_id'] != '') {
            $categoryId = $request['category_id'];
            $query = $query->whereHas('userBusinessCategories', function ($q) use ($categoryId) {
                $q->whereIn('business_category_id', $categoryId);
            });
        }

        if (isset($request['account_id']) && $request['account_id'] != '') {
            $query = $query->whereIn('id', $request['account_id']);
        }

        return $query->orderBy('created_at', 'DESC');
    }


    /**
     * Get Profile Detail account for CMS
     *
     * @param request
     *
     * @return model
     */
    public function getProfileAccountCms($id)
    {
        return $this->model->with(['roles', 'roles.permissions', 'followers', 'following', 'businessCategories', 'interestsTopic', 'bookmarkPosts'])->findOrFail($id);
    }
}
