<?php

namespace App\Repositories\Follow;

use App\Models\Follow;
use App\Enums\PointEnum;
use App\Enums\FollowEnum;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Repositories\BaseRepository;
use Illuminate\Database\DatabaseManager;

/**
 * Class FollowRepository
 *
 * @package App\Repositories\Follow
 */
class FollowRepository extends BaseRepository implements FollowRepositoryInterface
{
    /**
     * @var Follow
     */
    protected $model;

    /**
     * @var DatabaseManager
     */
    private $db;

    /**
     * FollowRepository constructor.
     *
     * @param Follow $model
     * @param DatabaseManager $db
     */
    public function __construct(
        Follow $model,
        DatabaseManager $db
    ) {
        parent::__construct($model);

        $this->db = $db;
    }

    /**
     * Follow/UnFollow user.
     *
     * @param [model] $user
     *
     * @return array
     */
    public function followUser($user)
    {
        $point = PointEnum::POINT_FOLLOW;
        $followerId = Auth::id();
        $isFollowedId = $user->id;

        $query = $this->model->whereFollowerId($followerId)
            ->whereIsFollowedId($isFollowedId);

        if ($query->exists()) {
            DB::beginTransaction();
            try {
                $status = $query->value('status');
                if ($status) {
                    $point = -$point;
                }
                $query->update(['status' => !$status]);
                $this->updatePoint($user, $point);
                DB::commit();

                $totalFollows = $this->model->whereIsFollowedId($isFollowedId)
                    ->whereStatus(FollowEnum::STATUS_ACTIVE)
                    ->count();

                return [
                    'status_code' => Response::HTTP_OK,
                    'total_follows' => $totalFollows,
                    'is_followed' => (boolean)!$status,
                    'user_id' => $isFollowedId
                ];
            } catch (\Exception $exception) {
                DB::rollBack();
                report($exception);

                return [
                    'status_code' => Response::HTTP_INTERNAL_SERVER_ERROR
                ];
            }
        } else {
            DB::beginTransaction();
            try {
                $query->create([
                    'follower_id' => $followerId,
                    'is_followed_id' => $isFollowedId,
                    'status' => FollowEnum::STATUS_ACTIVE,
                    'created_by' => $followerId,
                    'updated_by' => $followerId,
                ]);
                $this->updatePoint($user, $point);
                DB::commit();
                $totalFollows = $this->model->whereIsFollowedId($isFollowedId)
                    ->whereStatus(FollowEnum::STATUS_ACTIVE)
                    ->count();

                return [
                    'status_code' => Response::HTTP_OK,
                    'total_follows' => $totalFollows,
                    'is_followed' => (boolean)FollowEnum::STATUS_ACTIVE,
                    'user_id' => $isFollowedId
                ];
            } catch (\Exception $exception) {
                DB::rollBack();
                report($exception);

                return [
                    'status_code' => Response::HTTP_INTERNAL_SERVER_ERROR
                ];
            }
        }
    }

    /**
     * Remove user follower
     *
     * @param $user
     *
     * @return integer
     */
    public function removeFollower($user)
    {
        DB::beginTransaction();
        try {
            $authUser = Auth::user();
            $query = $user->following()->where(['is_followed_id' => $authUser->id]);
            if (!empty($query->value('status'))) {
                $this->updatePoint($authUser, -PointEnum::POINT_FOLLOW);
            }
            $query->update(['status' => FollowEnum::STATUS_INACTIVE]);
            DB::commit();

            return $user->id;
        } catch (\Exception $exception) {
            DB::rollBack();
            report($exception);

            return false;
        }
    }

    public function getByFollowerIds($ids)
    {
        return $this->model->whereIn('follower_id', $ids)->whereStatus(FollowEnum::STATUS_ACTIVE);
    }
}
