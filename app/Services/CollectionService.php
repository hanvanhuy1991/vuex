<?php

namespace App\Services;

use App\Enums\CollectionEnum;
use App\Enums\FavoriteEnum;
use App\Enums\UniEnum;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Collection\CollectionRepositoryInterface;

class CollectionService
{
    /**
     * @var $collectionRepository
     */
    protected $collectionRepository;

    /**
     * CollectionService constructor.
     *
     * @param CollectionRepositoryInterface $collectionRepository
     */
    public function __construct(CollectionRepositoryInterface $collectionRepository)
    {
        $this->collectionRepository = $collectionRepository;
    }

    /**
     * Get builder collection by auth user and type bookmark
     *
     * @return builder
     */
    public function index()
    {
        return $this->collectionRepository->index();
    }

    /**
     * Create collection
     *
     * @param $name
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function store($name)
    {
        $data = [
            'user_id' => Auth::id(),
            'name' => $name,
            'type' => CollectionEnum::BOOKMARK
        ];

        return $this->collectionRepository->create($data);
    }

    public function show($collection)
    {
        return Auth::user()->bookmarkPosts()
            ->where('collection_id', $collection)
            ->where('is_favorited', FavoriteEnum::IS_FAVORITE)
            ->with('bookmarks')
            ->has('bookmarks');
    }

    /**
     * Get uni all
     *
     * @return mixed
     */
    public function uniAll()
    {
        return Auth::user()->bookmarkPosts()
            ->limit(10)
            ->where('is_favorited', FavoriteEnum::IS_FAVORITE)
            ->with('bookmarks')
            ->has('bookmarks');
    }

    /**
     * Get uni book
     *
     * @return mixed
     */
    public function uniBook()
    {
        $authUser = Auth::user();

        return $authUser->collections()
            ->withCount(['bookmarks' => function ($q) use ($authUser) {
                $q->whereUserId($authUser->id)->whereIsFavorited(FavoriteEnum::IS_FAVORITE)
                    ->with('post')
                    ->has('post');
            }]);
    }
}
