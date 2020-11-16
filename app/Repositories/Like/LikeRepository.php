<?php

namespace App\Repositories\Like;

use App\Models\Like;
use App\Enums\LikeEnum;
use App\Enums\PointEnum;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Repositories\BaseRepository;
use Illuminate\Database\DatabaseManager;

/**
 * Class LikeRepository
 * @package App\Repositories\Like
 */
class LikeRepository extends BaseRepository implements LikeRepositoryInterface
{
    /**
     * @var Like
     */
    protected $model;

    /**
     * @var DatabaseManager
     */
    private $db;

    /**
     * LikeRepository constructor.
     *
     * @param Like $model
     * @param DatabaseManager $db
     */
    public function __construct(
        Like $model,
        DatabaseManager $db
    ) {
        parent::__construct($model);

        $this->db = $db;
    }

    /**
     * Like/Dislike post or comment.
     *
     * @param $model
     * @param $type
     *
     * @return array
     */
    public function actionLike($model, $type)
    {
        DB::beginTransaction();
        try {
            $pointLike = -PointEnum::POINT_LIKE;
            $pointHashTag = -PointEnum::POINT_HASH_TAG;
            $userId = Auth::id();
            $conditions = [
                'likeable_type' => $type,
                'likeable_id' => $model->id,
                'user_id' => $userId
            ];
            $isLiked = (boolean)$this->model->where($conditions)->value('is_liked');
            $createdBy = $type == LikeEnum::MODEL_COMMENT ? $model->user_id : $model->created_by;
            $values = [
                'is_liked' => LikeEnum::STATUS_DISLIKE,
                'created_by' => $createdBy,
            ];
            if (!$isLiked) {
                $pointLike = -$pointLike;
                $pointHashTag = -$pointHashTag;
                $values['is_liked'] = !$isLiked;
            }
            $this->updateOrCreate($conditions, $values);
            $this->updatePoint($model->user, $pointLike);
            if ($type == LikeEnum::MODEL_POST) {
                $hashTags = $model->hashTags;
                $this->updatePoint($model, $pointLike);
                if (!$isLiked) {
                    $this->update($model, ['action_time' => date('Y-m-d H:i:s')]);
                }
                foreach ($hashTags as $hashTag) {
                    $this->updatePoint($hashTag, $pointHashTag);
                }
            }
            DB::commit();
            $totalLike = $this->model->whereLikeableType($type)
                ->whereLikeableId($model->id)
                ->whereIsLiked(LikeEnum::STATUS_LIKE)
                ->count();

            return [
                'status_code' => Response::HTTP_OK,
                'total_like' => $totalLike,
                'is_liked' => !$isLiked,
                'model_id' => $model->id
            ];
        } catch (\Exception $exception) {
            DB::rollback();
            report($exception);

            return [
                'status_code' => Response::HTTP_INTERNAL_SERVER_ERROR
            ];
        }
    }

    public function getByPostIds($ids)
    {
        return $this->model->whereLikeableType(LikeEnum::MODEL_POST)
            ->whereIn('likeable_id', $ids)
            ->whereIsLiked(LikeEnum::STATUS_LIKE);
    }
}
