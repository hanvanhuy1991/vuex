<?php

namespace App\Repositories\Bookmark;

use App\Enums\FavoriteEnum;
use App\Enums\PointEnum;
use App\Http\Resources\UniBookResource;
use App\Models\Bookmark;
use Illuminate\Support\Facades\Auth;
use App\Repositories\BaseRepository;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Facades\DB;

class BookmarkRepository extends BaseRepository implements BookmarkRepositoryInterface
{
    /**
     * @var Bookmark
     */
    protected $model;

    /**
     * @var DatabaseManager
     */
    private $db;

    /**
     * CommentRepository constructor.
     * @param Bookmark $model
     * @param DatabaseManager $db
     */
    public function __construct(
        Bookmark $model,
        DatabaseManager $db
    ) {
        parent::__construct($model);

        $this->db = $db;
    }

    /**
     * Bookmark/UnBookmark  and update point bookmark
     *
     * @param $model
     * @param $request
     *
     * @return array
     */
    public function store($model, $request)
    {
        DB::beginTransaction();
        try {
            $authUser = Auth::user();
            $point = -PointEnum::POINT_BOOKMARK;
            $pointHashTag = -PointEnum::POINT_HASH_TAG;
            $hashTags = $model->hashTags;
            $conditions = [
                'user_id' => $authUser->id,
                'post_id' => $model->id
            ];
            $isFavorite = $request->is_favorite;
            $values = [
                'is_favorited' => $isFavorite,
            ];
            if ($isFavorite) {
                $currentId = $request->collection_id;
                $point = -$point;
                $pointHashTag = -$pointHashTag;
                $values['collection_id'] = $request->collection_id;
            } else {
                $currentId = $authUser->bookmarks()->wherePostId($model->id)->value('collection_id');
            }
            $this->updateOrCreate($conditions, $values);
            $this->updatePoint($model, $point);
            $this->updatePoint($model->user, $point);
            foreach ($hashTags as $hashTag) {
                $this->updatePoint($hashTag, $pointHashTag);
            }
            DB::commit();

            $collections = $authUser->collections()
                ->whereId($currentId)
                ->withCount(['bookmarks' => function ($q) use ($authUser) {
                    $q->whereUserId($authUser->id)->whereIsFavorited(FavoriteEnum::IS_FAVORITE)
                        ->with('post')
                        ->has('post');
                }])
                ->first();
            return [
                'slug' => $model->slug,
                'is_bookmarked' => $isFavorite,
                'total_bookmarks' => $model->bookmarks()->whereIsFavorited(FavoriteEnum::IS_FAVORITE)->count(),
                'collections' => new UniBookResource($collections)
            ];
        } catch (\Exception $exception) {
            DB::rollback();
            report($exception);

            return [];
        }
    }
}
