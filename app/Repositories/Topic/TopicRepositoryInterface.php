<?php

namespace App\Repositories\Topic;

use App\Repositories\BaseRepositoryInterface;

/**
 * Interface TopicRepositoryInterface
 *
 * @package App\Repositories\Topic
 */
interface TopicRepositoryInterface extends BaseRepositoryInterface
{

    /**
     * Get list top ranking
     *
     * @return $collection
     */
    public function hotRanking();

    /**
     * Check topic and Category exist
     *
     * @param $request
     *
     * @return $model
     */
    public function checkTopicAndCategoryExist($request);

    /**
     *  Get topic and Category by Id
     *
     * @param $categoryId
     * @param $topicName
     *
     * @return $model
     */
    public function getTopicAndCategoryById($categoryId, $topicName);

    /**
     *  Search Topic with keyword
     *
     * @param string $keyword
     *
     * @return $model
     */
    public function search($keyword);

    /**
     *  Search Topic with keyword and categoryId
     *
     * @param string $keyword
     * @param string $categoryId
     *
     * @return $collection
     */
    public function searchTopicWithCategory($keyword, $categoryId);

    /**
     * Get by hash tag name
     *
     * @param $keyword
     *
     * @return builder
     */
    public function getByHashTagName($keyword);

    /**
     * Get ids by search like
     *
     * @param $keywords
     *
     * @return builder
     */
    public function getBySearchLike($keywords);

    /**
     * Query get data by ids
     *
     * @param $ids
     *
     * @return builder
     */
    public function getByIds($ids);

    /**
     * @param $topicIds
     *
     * @return builder
     */
    function getByCategoryIds($topicIds);
}
