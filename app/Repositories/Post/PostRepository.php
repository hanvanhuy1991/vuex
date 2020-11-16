<?php

namespace App\Repositories\Post;

use App\Enums\BlockEnum;
use Carbon\Carbon;
use App\Models\Post;
use App\Enums\LikeEnum;
use App\Enums\PostEnum;
use App\Enums\UserRole;
use App\Enums\PointEnum;
use App\Enums\FavoriteEnum;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\DatabaseManager;
use App\Repositories\User\UserRepositoryInterface;

/**
 * Class PostRepository
 *
 * @package App\Repositories\User
 */
class PostRepository extends BaseRepository implements PostRepositoryInterface
{
    private $db;
    protected $model;
    protected $userRepository;

    /**
     * PostRepository constructor.
     *
     * @param Post $model
     * @param DatabaseManager $db
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(
        Post $model,
        DatabaseManager $db,
        UserRepositoryInterface $userRepository
    ) {
        parent::__construct($model);

        $this->db = $db;
        $this->userRepository = $userRepository;
    }

    /**
     * Get post by created_by ids
     *
     * @param $ids
     *
     * @return builder
     */
    public function getPostsUserIds($ids)
    {
        $query = $this->model->whereIn('created_by', $ids);
        $withRelations = ['hashTags', 'user', 'images', 'videos', 'images.imageTags', 'images.imageTags.user'];
        $withCountRelations = ['shares', 'comments'];

        return $this->getRelations($query, $withRelations, $withCountRelations);
    }

    /**
     * @param $postIds
     * @param $sort
     *
     * @return bulider
     */
    public function getPostByDiscovery($postIds, $sort)
    {
        $orderBy = $this->userRepository->getOrderByToPost();
        $query = $this->getByIds($postIds);
        $withRelations = ['hashTags', 'user'];
        $withCountRelations = ['shares', 'comments'];
        $query = $this->getRelations($query, $withRelations, $withCountRelations);

        return $this->checkSort($query, $sort, $orderBy);
    }

    private function checkSort($query, $sort, $orderBy)
    {
        switch ($sort) {
            case PostEnum::SORT_BY_BUSINESS:
                $userIds = $this->userRepository->getUserByRole(UserRole::BUSINESS_ACCOUNT)
                    ->pluck('id')->toArray();
                $query = $query->whereHas('user', function ($q) use ($userIds) {
                    $q->whereIn('id', $userIds);
                })->orderBy($orderBy, 'DESC');
                break;

            case PostEnum::SORT_BY_UNICER:
                $userIds = $this->userRepository->getUserByRole(UserRole::UNICER)
                    ->pluck('id')->toArray();
                $query = $query->whereHas('user', function ($q) use ($userIds) {
                    $q->whereIn('id', $userIds);
                })->orderBy($orderBy, 'DESC');
                break;

            case PostEnum::SORT_BY_HOT:
                $query = $query->orderBy('point', 'DESC');
                break;

            case PostEnum::SORT_BY_LATEST:
                $query = $query->latest();
                break;

            default:
                $query = $query->orderBy($orderBy, 'DESC');
                break;
        }

        return $query;
    }

    /**
     * Get post nearby
     *
     * @param $lat
     * @param $long
     *
     * @return builder
     */
    public function getPostNearby($lat, $long)
    {
        $raw = 'ABS(location_lat - '.$lat.') + ABS(location_long - '.$long.') as sub';
        $query = $this->model->select(['*', DB::raw($raw)])->orderBy('sub', 'ASC');
        $withRelations = ['hashTags', 'user'];
        $withCountRelations = ['shares', 'comments'];

        return $this->getRelations($query, $withRelations, $withCountRelations);
    }

    /**
     * @param $query
     * @param $withRelations
     * @param $withCountRelations
     *
     * @return builder
     */
    private function getRelations($query, $withRelations, $withCountRelations)
    {
        $authUser = Auth::user();
        $idsHidden = $authUser->hiddenPosts()->pluck('hiddenable_id')->toArray();
        $userInvalidIds = $authUser->blockers()
            ->whereStatus(BlockEnum::STATUS_BLOCK)
            ->pluck('is_blocked_id')
            ->toArray();

        return $query->where('status', PostEnum::STATUS_ACTIVE)
            ->whereNotIn('posts.id', $idsHidden)
            ->whereNotIn('posts.created_by', $userInvalidIds)
            ->with($withRelations)
            ->withCount($withCountRelations)
            ->with(['likes' => function ($q) {
                $q->whereIsLiked(LikeEnum::STATUS_LIKE);
            }])
            ->with(['bookmarks' => function ($q) {
                $q->whereIsFavorited(FavoriteEnum::IS_FAVORITE);
            }])
            ->where(function ($q) {
                $q->has('videos')->orHas('images');
            })->has('user');
    }

    /**
     * @param $content
     *
     * @return mixed
     */
    public function getHashTagsBySearch($content)
    {
        $columns = [
            'content',
            'title',
            'location_name'
        ];
        $query = $this->model;
        $query = $query->fullTextSearch($query, $columns, $content)->with('hashTags')->get();

        return $this->getRandom($query, PostEnum::QTY_RANDOM_BY_LAST_SEARCH);
    }

    /**
     * @param $query
     * @param $qty
     *
     * @return mixed
     */
    public function getRandom($query, $qty)
    {
        if ($query->count() >= $qty) {
            $query = $query->random($qty);
        }

        return $query;
    }

    /**
     * Get detail post by id.
     *
     * @param $slug
     *
     * @return [collection] post
     */
    public function show($slug)
    {
        $withRelations = ['hashTags', 'user', 'images', 'videos', 'images.imageTags', 'images.imageTags.user'];
        $withCountRelations = ['shares', 'comments'];
        $query = $this->findBySlug($slug);

        $query = $this->getRelations($query, $withRelations, $withCountRelations);
        if (!empty($query->exists())) {
            $this->updatePoints($query);
        }

        return $query->get();
    }

    /**
     * Update point of post.
     *
     * @param model $query
     *
     * return void
     */
    private function updatePoints($query)
    {
        $post = $query->first();
        $post->increment('view');
        $this->updatePoint($post, PointEnum::POINT_VIEW);
        $this->updatePoint($post->user, PointEnum::POINT_VIEW);
        $hashTags = $post->hashTags;
        foreach ($hashTags as $hashTag) {
            $this->updatePoint($hashTag, PointEnum::POINT_VIEW);
        }
    }

    /**
     * Get absolute by title, content or location
     *
     * @param $keyword
     *
     * @return builder
     */
    public function absoluteSearchByKeyword($keyword)
    {
        return $this->model->whereTitle($keyword)
            ->orWhere('content', $keyword)
            ->orWhere('location_name', $keyword);
    }

    /**
     * Get like by title, content or location
     *
     * @param array $keywords
     *
     * @return builder
     */
    public function getBySearchLike($keywords)
    {
        $columns = [
            'content',
            'title',
            'location_name'
        ];
        $query = $this->model;

        return $query->fullTextSearchByArray($query, $columns, $keywords);
    }

    /**
     * Get data by ids
     *
     * @param $ids
     *
     * @return builder
     */
    public function searchPost($ids)
    {
        $query = $this->getByIds($ids);
        $withRelations = ['hashTags', 'user'];
        $withCountRelations = ['shares', 'comments'];

        return $this->getRelations($query, $withRelations, $withCountRelations);
    }

    /**
     * Get data by ids
     *
     * @param $ids
     *
     * @return builder
     */
    public function getByIds($ids)
    {
        return $this->model->whereIn('posts.id', $ids);
    }

    /**
     * Get data by ids
     *
     * @param $ids
     *
     * @return builder
     */
    public function getPostsLikedByUserId($ids)
    {
        $query = $this->getByIds($ids);
        $withRelations = ['hashTags', 'user'];
        $withCountRelations = ['shares', 'comments'];

        return $this->getRelations($query, $withRelations, $withCountRelations)
            ->join('likes', 'likes.likeable_id', '=', 'posts.id')
            ->orderBy('likes.updated_at', 'DESC');
    }

    /**
     * Get data by ids
     *
     * @param $ids
     *
     * @return builder
     */
    public function getPostsBookmarkedByUser($ids)
    {
        $query = $this->getByIds($ids);
        $withRelations = ['hashTags', 'user', 'images', 'videos'];
        $withCountRelations = ['shares', 'comments'];

        return $this->getRelations($query, $withRelations, $withCountRelations)
            ->join('favorites', 'favorites.post_id', '=', 'posts.id')
            ->orderBy('favorites.updated_at', 'DESC');
    }

    /**
     * Share Post
     *
     * @param [model] $post
     * @param [string] $type
     *
     * @return boolean
     */
    public function share($post, $type)
    {
        DB::beginTransaction();
        try {
            $userId = Auth::id();
            $post->shares()->attach($userId, [
                'share_date' => date('Y-m-d H:i:s'),
                'type' => $type,
                'created_by' => $userId,
                'updated_by' => $userId
            ]);

            $this->updatePoint($post, PointEnum::POINT_SHARE);
            $this->updatePoint($post->user, PointEnum::POINT_SHARE);
            $topics = $post->hashTags;
            foreach ($topics as $topic) {
                $this->updatePoint($topic, PointEnum::POINT_SHARE);
            }
            DB::commit();

            return true;
        } catch (\Exception $exception) {
            DB::rollback();
            report($exception);

            return false;
        }
    }
    // CMS Funtion

    /**
     * Get list Post
     *
     * @param [model] $post
     * @param [string] $type
     *
     * @return boolean
     */
    public function getListPostCms()
    {
        return $this->model->with(['user', 'bookmarks', 'comments', 'likes', 'bookmarks', 'hashTags', 'shares'])->has('user')->orderBy('created_at', 'DESC');
    }

     /**
     * Search Post
     *
     * @param [model] $post
     * @param [string] $type
     *
     * @return boolean
     */
    public function searchPosts($request)
    {
        $post = $this->model->with(['user', 'bookmarks', 'comments', 'likes', 'bookmarks', 'hashTags', 'draftPost', 'shares']);
        if (isset($request['account_id'])) {
            $post = $post->whereIn('created_by', $request['account_id']);
        }

        if (isset($request['status'])) {
            $post = $post->whereStatus($request['status']);
        }

        if (isset($request['title'])) {
            $post = $post->where('title', 'like', '%'.$request['title'].'%');
        }

        if (isset($request['content'])) {
            $post = $post->where('content', 'like', '%'.$request['content'].'%');
        }
        $start = $request['start_date'] ?? null;
        $end = $request['end_date'] ?? null;

        if ($start) {
            $post = $post->where('created_at', '>=', $start);
        }

        if ($end) {
            $post = $post->where('created_at', '<=', $end);
        }

        return $post->has('user')->orderBy('created_at', 'DESC');
    }

    /**
     * Get detail post by slug cms.
     *
     * @param $slug
     *
     * @return [collection] post
     */
    public function showPostsCms($slug)
    {
        return $this->model->with(['user', 'bookmarks', 'comments', 'videos','images', 'likes', 'bookmarks', 'hashTags', 'shares', 'draftPost','images.imageTags', 'images.imageTags.user'])->whereSlug($slug)->firstOrFail();
    }

    /**
     * Get list post for account with account id.
     *
     * @param $slug
     *
     * @return [collection] post
     */
    public function getListPostWithAccountCms($accountId)
    {
        return $this->model->where('created_by', '=', $accountId)->with(['user', 'bookmarks', 'comments', 'likes', 'bookmarks', 'hashTags', 'draftPost', 'shares'])->has('user')->orderBy('created_at', 'DESC');
    }
}
