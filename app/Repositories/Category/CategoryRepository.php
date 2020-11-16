<?php

namespace App\Repositories\Category;

use App\Models\Topic;
use App\Repositories\BaseRepository;
use Illuminate\Database\DatabaseManager;

/**
 * Class CategoryRepository
 *
 * @package App\Repositories\Topic
 */
class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface
{
    /**
     * @var Topic
     */
    protected $model;

    /**
     * @var DatabaseManager
     */
    private $db;

    /**
     * CategoryRepository constructor.
     *
     * @param Topic $model
     * @param DatabaseManager $db
     */
    public function __construct(Topic $model, DatabaseManager $db)
    {
        parent::__construct($model);

        $this->db = $db;
    }

    /**
     * Get All Category with Topic
     *
     * @param string $seeMore
     *
     * @return collection
     */
    public function getCategoryWithTopic($hotest)
    {
        $query = $this->model->whereHas('hashTags', function ($q) {
            $q->orderBy('point', 'DESC');
        });

        if ($hotest == 'true') {
            $query = $query->limit(5);
        }

        $query = $query->with(['hashTags' => function ($q) {
            $q->orderBy('point', 'DESC');
        }])->get()->map(function ($query) {
            $query->setRelation('hashTags', $query->hashTags->take(10));
            return $query;
        });

        return $query;
    }

    /**
     * Search Category with keyword
     *
     * @param string $keyword
     *
     * @return $collection
     */
    public function search($keyword)
    {
        return $this->model->where('title', 'like', '%' . $keyword . '%')->get();
    }
}
