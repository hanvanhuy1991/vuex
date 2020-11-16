<?php

namespace App\Services;

use App\Enums\FavoriteEnum;
use App\Repositories\Bookmark\BookmarkRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class BookmarkService
{
    /**
     * @var $bookmarkRepository
     */
    protected $bookmarkRepository;

    /**
     * BookmarkService constructor.
     *
     * @param BookmarkRepositoryInterface $bookmarkRepository
     */
    public function __construct(BookmarkRepositoryInterface $bookmarkRepository)
    {
        $this->bookmarkRepository = $bookmarkRepository;
    }

    /**
     * Bookmark/UnBookmark
     *
     * @param $model
     * @param $request
     *
     * @return boolean
     */
    public function store($model, $request)
    {
        return $this->bookmarkRepository->store($model, $request);
    }

    /**
     * Move bookmark
     *
     * @param $post
     * @param $collectionId
     *
     * @return mixed
     */
    public function move($post, $collectionId)
    {
        $authUser = Auth::user();
        $bookmarks = $authUser->bookmarks()->wherePostId($post->id);
        $oldCollectionId = $bookmarks->value('collection_id');
        $bookmarks->update([
            'collection_id' => $collectionId,
            'is_favorited' => FavoriteEnum::IS_FAVORITE
        ]);

        return $authUser->collections()
            ->whereIn('id', [$oldCollectionId, $collectionId])
            ->withCount(['bookmarks' => function ($q) use ($authUser) {
                $q->whereUserId($authUser->id)->whereIsFavorited(FavoriteEnum::IS_FAVORITE)
                    ->with('post')
                    ->has('post');
            }])
            ->get();
    }
}
