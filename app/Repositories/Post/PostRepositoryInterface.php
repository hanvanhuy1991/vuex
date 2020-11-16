<?php

namespace App\Repositories\Post;

use App\Repositories\BaseRepositoryInterface;

/**
 * Interface PostRepositoryInterface
 *
 * @package App\Gmt\Repositories\Post
 */
interface PostRepositoryInterface extends BaseRepositoryInterface
{
    public function getPostsUserIds($ids);

    public function getPostByDiscovery($postIds, $sort);

    public function getPostNearby($lat, $long);

    public function show($post);

    public function getHashTagsBySearch($content);

    /**
     * Get absolute by title, content or location
     *
     * @param string $keyword
     *
     * @return builder
     */
    public function absoluteSearchByKeyword($keyword);

    /**
     * Get like by title, content or location
     *
     * @param array $keywords
     *
     * @return builder
     */
    public function getBySearchLike($keywords);

    /**
     * Get data by ids
     *
     * @param $ids
     *
     * @return builder
     */
    public function searchPost($ids);

    /**
     * Get data by ids
     *
     * @param $ids
     *
     * @return builder
     */
    public function getByIds($ids);

    /**
     * Get data by ids
     *
     * @param $ids
     *
     * @return builder
     */
    public function getPostsLikedByUserId($ids);

    /**
     * Get data by ids
     *
     * @param $ids
     *
     * @return builder
     */
    public function getPostsBookmarkedByUser($ids);

    /**
     * Share Post
     *
     * @param [model] $post
     * @param [string] $type
     *
     * @return boolean
     */
    public function share($post, $type);

    /**
     * Search Post
     *
     * @param $request
     *
     * @return builder
     */
    public function searchPosts($request);

    /**
     * Show post detail Post
     *
     * @param [model] $post
     * @param [string] $type
     *
     * @return boolean
     */
    public function showPostsCms($slug);
}
