<?php

namespace App\Repositories\Favorite;

use App\Models\Topic;
use App\Repositories\BaseRepository;
use Illuminate\Database\DatabaseManager;

/**
 * Class FavoriteRepository
 *
 * @package App\Repositories\Favorite
 */
class FavoriteRepository extends BaseRepository implements FavoriteRepositoryInterface
{
    /**
     * @var Topic as Favorite
     */
    protected $model;

    /**
     * @var DatabaseManager
     */
    private $db;

    /**
     * TopicRepository constructor.
     *
     * @param Topic as Favorite $model
     * @param DatabaseManager $db
     */
    public function __construct(Topic $model, DatabaseManager $db)
    {
        parent::__construct($model);

        $this->db = $db;
    }

    public function getFavorite()
    {
        return $this->model->all();
    }
}
