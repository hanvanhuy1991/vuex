<?php

namespace App\Repositories\Topic;

use App\Models\HashTag;
use App\Repositories\BaseRepository;
use Illuminate\Database\DatabaseManager;

/**
 * Class TopicRepository
 *
 * @package App\Repositories\Topic
 */
class TopicRepository extends BaseRepository implements TopicRepositoryInterface
{
    /**
     * @var HashTag
     */
    protected $model;

    /**
     * @var DatabaseManager
     */
    private $db;

    /**
     * TopicRepository constructor.
     *
     * @param HashTag $model
     * @param DatabaseManager $db
     */
    public function __construct(HashTag $model, DatabaseManager $db)
    {
        parent::__construct($model);

        $this->db = $db;
    }

    /**
     * Get list top ranking
     *
     * @return $collection
     */
    public function hotRanking()
    {
        return $this->model->orderBy('point', 'DESC')->limit(10)->get();
    }

    /**
     * Check topic and Category exist
     *
     * @param $request
     *
     * @return $model
     */
    public function checkTopicAndCategoryExist($request)
    {
        $categoryId = $request['category_id'];
        return $this->model->where('hash_tag_name', $request['topic_name'])->whereHas('topic', function ($q) use ($categoryId) {
            $q->where('id', $categoryId);
        })->with('topic')->get();
    }

    /**
     *  Get topic and Category by Id
     *
     * @param $categoryId
     * @param $topicName
     *
     * @return $model
     */
    public function getTopicAndCategoryById($categoryId, $topicName)
    {
        return $this->model->where('hash_tag_name', $topicName)->whereHas('topic', function ($q) use ($categoryId) {
            $q->where('id', $categoryId);
        })->with(['topic' => function ($q) use ($categoryId) {
            $q->where('id', $categoryId);
        }])->first();
    }

    /**
     *  Search Topic with keyword
     *
     * @param string $keyword
     *
     * @return $model
     */
    public function search($keyword)
    {
        return $this->model->where('hash_tag_name', 'like', '%' . $keyword . '%')->with('topic')->orderBy('point', 'DESC');
    }

    /**
     *  Search Topic with keyword and categoryId
     *
     * @param string $keyword
     * @param string $categoryId
     *
     * @return $collection
     */
    public function searchTopicWithCategory($keyword, $categoryId)
    {
        return $this->model->where('hash_tag_name', 'like', '%' . $keyword . '%')
            ->where('topic_id', $categoryId)
            ->with('topic')
            ->orderBy('point', 'DESC');
    }

    /**
     * Get by hash tag name
     *
     * @param $keyword
     *
     * @return builder
     */
    public function getByHashTagName($keyword)
    {
        return $this->model->whereHashTagName($keyword);
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
                $q->orWhere('hash_tag_name', 'like', $like);
            }
        });
    }

    /**
     * Query get data by ids
     *
     * @param $ids
     *
     * @return builder
     */
    public function getByIds($ids)
    {
        return $this->model->whereIn('id', $ids);
    }

    /**
     * @param $topicIds
     *
     * @return builder
     */
    function getByCategoryIds($topicIds)
    {
        return $this->model->whereIn('topic_id', $topicIds);
    }
}
