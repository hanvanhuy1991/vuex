<?php

namespace App\Repositories\Search;

use App\Enums\SearchEnum;
use App\Models\SearchHistory;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\DatabaseManager;

class SearchRepository extends BaseRepository implements SearchRepositoryInterface
{
    /**
     * @var SearchHistory
     */
    protected $model;

    /**
     * @var DatabaseManager
     */
    private $db;

    /**
     * SearchRepository constructor.
     * @param SearchHistory $model
     * @param DatabaseManager $db
     */
    public function __construct(
        SearchHistory $model,
        DatabaseManager $db
    ) {
        parent::__construct($model);

        $this->db = $db;
    }

    /**
     * Get search history maximum 8 items
     *
     * @return collection
     */
    public function histories()
    {
        return $this->model->select(['search_content'])
            ->whereUserId(Auth::id())
            ->limit(SearchEnum::LIMIT_HISTORIES)
            ->latest()
            ->get();
    }

    /**
     * Clear all search history of auth user
     *
     * @return boolean
     */
    public function clearHistory()
    {
        return $this->model->whereUserId(Auth::id())->delete();
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
                $q->orWhere('search_content', 'like', $like);
            }
        });
    }
}
