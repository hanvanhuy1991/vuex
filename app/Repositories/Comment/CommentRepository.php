<?php

namespace App\Repositories\Comment;

use App\Enums\MentionEnum;
use App\Models\Comment;
use App\Enums\ImageEnum;
use App\Enums\LastAction;
use App\Enums\CommentEnum;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Repositories\BaseRepository;
use Illuminate\Database\DatabaseManager;

/**
 * Class CommentRepository
 * @package App\Repositories\Comment
 */
class CommentRepository extends BaseRepository implements CommentRepositoryInterface
{
    /**
     * @var Comment
     */
    protected $model;

    /**
     * @var DatabaseManager
     */
    private $db;

    /**
     * CommentRepository constructor.
     * @param Comment $model
     * @param DatabaseManager $db
     */
    public function __construct(
        Comment $model,
        DatabaseManager $db
    ) {
        parent::__construct($model);

        $this->db = $db;
    }

    /**
     * @param $postId
     * @return mixed|void
     */
    public function getCommentForPost($postId)
    {
        return $this->model->whereCommentableId($postId)
            ->select('id', 'user_id', 'content', 'created_at')
            ->whereCommentableType(CommentEnum::MODEL_POST)
            ->whereNull('parent_id')
            ->with(['images', 'videos', 'user', 'mentions', 'mentions.user'])
            ->with(['childComments' => function ($q) {
                $q->select('id', 'parent_id', 'user_id', 'content', 'created_at')
                    ->with(['images', 'user']);
            }])
            ->latest();
    }

    /**
     * Create comment
     *
     * @param [model] $post
     * @param [array] $data
     *
     * @return array
     */
    public function store($post, $data)
    {
        $postId = $post->id;
        $users = Auth::user();
        DB::beginTransaction();
        try {
            $lastActions = $users->lastActions();
            $lastActions->create([
                'user_id' => $users->id,
                'post_id' => $postId,
                'type' => LastAction::TYPE_COMMENT,
                'created_by' => $users->id
            ]);
            $subAction = $lastActions->count() - LastAction::MAX_ACTION;

            if ($subAction >= 0) {
                $lastActions->oldest()->limit($subAction)->delete();
            }

            $query = $this->model->create([
                'parent_id' => $data['parent_id'] ?? null,
                'user_id' => $users->id,
                'commentable_type' => CommentEnum::MODEL_POST,
                'commentable_id' => $postId,
                'content' => $data['content'] ?? null,
                'created_by' => $users->id
            ]);

            $query->users = [
                'id' => $users->id,
                'name' => $users->name,
                'avatar' => $users->avatar
            ];

            if (!empty($data['images'])) {
                $query->images()->create([
                    'user_id' => $users->id,
                    'imageable_type' => ImageEnum::MODEL_COMMENT,
                    'image_path' => $data['images']['url'],
                    'created_by' => $users->id,
                    'width' => $data['images']['width'],
                    'height' => $data['images']['height']
                ]);
            }
//            if (!empty($data['videos'])) {
//                $query->videos()->create([
//                    'user_id' => $users->id,
//                    'videoable_type' => ImageEnum::MODEL_COMMENT,
//                    'video_path' => $data['videos']['url'],
//                    'created_by' => $users->id,
//                    'width' => $data['videos']['width'],
//                    'height' => $data['videos']['height']
//                ]);
//            }

            if (!empty($data['mentions'])) {
                $this->storeMentions($query->mentions(), $data['mentions']);
            }

            $this->update($post, ['action_time' => date('Y-m-d H:i:s')]);
            DB::commit();

            return [
                'status_code' => Response::HTTP_OK,
                'data' => $this->setRelations($this->model->whereId($query->id))->get()
            ];
        } catch (\Exception $exception) {
            DB::rollBack();
            report($exception);

            return [
                'status_code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'data' => []
            ];
        }
    }

    /**
     * Update comment
     *
     * @param [model] $model
     * @param [array] $data
     *
     * @return array
     */
    public function updateBasic($model, $data)
    {
        DB::beginTransaction();
        try {
            $model->update(['content' => $data['content']]);
            $mentions = $model->mentions();
            $mentions->delete();
            if (!empty($data['mentions'])) {
                $this->storeMentions($mentions, $data['mentions']);
            }
            DB::commit();

            return [
                'status_code' => Response::HTTP_OK,
                'data' => $this->setRelations($this->model->whereId($model->id))->get()
            ];
        } catch (\Exception $exception) {
            DB::rollBack();
            report($exception);

            return [
                'status_code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'data' => []
            ];
        }
    }

    private function setRelations($query)
    {
        return $query->with(['user', 'images', 'videos', 'mentions', 'mentions.user']);
    }

    private function storeMentions($modelMentions, $mentions)
    {
        $insertMentions = [];
        foreach ($mentions as $mention) {
            $insertMentions[] = [
                'user_id' => $mention,
                'mentionable_type' => MentionEnum::COMMENT_MODEL
            ];
        }
        $modelMentions->createMany($insertMentions);
    }

    public function getByPostIds($ids)
    {
        return $this->model->whereCommentableType(CommentEnum::MODEL_POST)->whereIn('commentable_id', $ids);
    }
}
