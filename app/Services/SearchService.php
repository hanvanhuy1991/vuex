<?php

namespace App\Services;

use App\Enums\BlockEnum;
use App\Enums\SearchEnum;
use App\Repositories\Post\PostRepositoryInterface;
use App\Repositories\Search\SearchRepositoryInterface;
use App\Repositories\Topic\TopicRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use mysql_xdevapi\Collection;

class SearchService
{
    /**
     * @var $searchRepository
     */
    protected $searchRepository;

    /**
     * @var $topicRepository
     */
    protected $topicRepository;

    /**
     * @var $topicRepository
     */
    protected $userRepository;

    /**
     * @var $postRepository
     */
    protected $postRepository;

    /**
     * SearchService constructor.
     *
     * @param SearchRepositoryInterface $searchRepository
     * @param TopicRepositoryInterface $topicRepository
     * @param UserRepositoryInterface $userRepository
     * @param PostRepositoryInterface $postRepository
     */
    public function __construct(
        SearchRepositoryInterface $searchRepository,
        TopicRepositoryInterface $topicRepository,
        UserRepositoryInterface $userRepository,
        PostRepositoryInterface $postRepository
    ) {
        $this->searchRepository = $searchRepository;
        $this->topicRepository = $topicRepository;
        $this->userRepository = $userRepository;
        $this->postRepository = $postRepository;
    }

    /**
     * Get search history maximum 8 items
     *
     * @return collection
     */
    public function histories()
    {
        return $this->searchRepository->histories();
    }

    /**
     * Clear all search history of auth user
     *
     * @return boolean
     */
    public function clearHistory()
    {
        return $this->searchRepository->clearHistory();
    }

    /**
     * Get paginate topic by keyword.
     *
     * @param $keyword
     * @param $priority
     *
     * @return builder
     */
    public function getTopic($keyword, $priority)
    {
        $ids = $this->getPluckId($this->topicRepository->getByHashTagName($keyword));
        if ($priority != SearchEnum::TYPE_PRIORITY_FIRST) {
            $secondIds = $this->getPluckId($this->topicRepository->getBySearchLike([$keyword]));
            $ids = array_diff($secondIds, $ids);
            if ($priority == SearchEnum::TYPE_PRIORITY_THIRD) {
                $thirdIds = $this->getPluckId($this->topicRepository->getBySearchLike(explode(' ', $keyword)));
                $ids = array_diff($thirdIds, $secondIds);
            }
        }
        $this->store($keyword);

        return $this->topicRepository->getByIds($ids)->orderBy('point', 'DESC');
    }

    /**
     * Get paginate people by keyword.
     *
     * @param $keyword
     * @param $priority
     *
     * @return builder
     */
    public function getPeople($keyword, $priority)
    {
        $this->store($keyword);

        return $this->userRepository->getByIds($this->getIdsByPriority($keyword, $priority))
            ->withCount('followers')
            ->orderBy('point', 'DESC');
    }

    /**
     * Get paginate user by keyword.
     *
     * @param $keyword
     * @param $priority
     *
     * @return builder
     */
    public function getUser($keyword, $priority)
    {
        $this->store($keyword);

        return $this->userRepository->getBusinessAccountByIds($this->getIdsByPriority($keyword, $priority))
            ->withCount('followers')
            ->orderBy('point', 'DESC');
    }

    /**
     * Get suggest by keyword.
     *
     * @param $keyword
     *
     * @return builder
     */
    public function getSuggest($keyword)
    {
        $userInvalidIds = Auth::user()->blockers()
            ->whereStatus(BlockEnum::STATUS_BLOCK)
            ->pluck('is_blocked_id')
            ->toArray();

        $content = 'content';
        $users = $this->userRepository->getBySearchLike([$keyword])
            ->select(['id', 'name as '.$content, 'avatar'])
            ->whereNotIn('id', $userInvalidIds)
            ->limit(SearchEnum::LIMIT_SUGGEST)
            ->orderBy('point', 'DESC')
            ->get()
            ->toArray();

        $topics = $this->topicRepository->getBySearchLike([$keyword])
            ->select(['id', 'hash_tag_name as '.$content])
            ->limit(SearchEnum::LIMIT_SUGGEST)
            ->orderBy('point', 'DESC')
            ->get()
            ->toArray();

        $lastSearch = $this->searchRepository->getBySearchLike([$keyword])
            ->select(['id', 'search_content as '.$content])
            ->limit(SearchEnum::LIMIT_SUGGEST)
            ->latest()
            ->get()
            ->toArray();

        $response = array_merge($users, $topics, $lastSearch);
        shuffle($response);

        return $response;
    }

    /**
     * Get post by keyword.
     *
     * @param $keyword
     * @param $priority
     *
     * @return builder
     */
    public function getPost($keyword, $priority)
    {
        $idsByTopic = $this->getIdsByTopicName($keyword);
        $ids = $this->getPluckId($this->postRepository->absoluteSearchByKeyword($keyword));
        $ids = array_unique(array_merge($ids, $idsByTopic));
        if ($priority != SearchEnum::TYPE_PRIORITY_FIRST) {
            $secondIds = $this->getPluckId($this->postRepository->getBySearchLike([$keyword]));
            $ids = array_diff($secondIds, $ids);
            if ($priority == SearchEnum::TYPE_PRIORITY_THIRD) {
                $thirdIds = $this->getPluckId($this->postRepository->getBySearchLike(explode(' ', $keyword)));
                $ids = array_diff($thirdIds, $secondIds);
            }
        }
        $this->store($keyword);

        return $this->postRepository->searchPost($ids)->orderBy('point', 'DESC');
    }

    /**
     * Get pluck id
     *
     * @param $query
     *
     * @return array
     */
    private function getPluckId($query)
    {
        return $query->pluck('id')->toArray();
    }

    /**
     * Create search history
     *
     * @param $content
     */
    private function store($content)
    {
        $userId = Auth::id();
        $conditions = [
            'user_id' => $userId,
            'search_content' => $content,
        ];
        $values = array_merge($conditions, ['created_at' => date('Y-m-d H:i:s')]);
        $this->searchRepository->updateOrCreate($conditions, $values);
    }

    /**
     * Get paginate brand by keyword.
     *
     * @param $keyword
     *
     * @return builder
     */
    public function getBrand($keyword)
    {
        $limit = SearchEnum::LIMIT_BRAND;
        $firstIdsValid = $this->getPluckId($this->userRepository->getByName($keyword));
        $secondIds = $this->getPluckId($this->userRepository->getBySearchLike([$keyword]));
        $thirdIds = $this->getPluckId($this->userRepository->getBySearchLike(explode(' ', $keyword)));

        $secondIdsValid = array_diff($secondIds, $firstIdsValid);
        $thirdIdsValid = array_diff($thirdIds, $secondIds);

        $firstCollections = $this->getUserBrand($firstIdsValid, $limit);
        $firstCount = $firstCollections->count();

        if ($firstCount < $limit) {
            $secondLimit = $limit - $firstCount;
            $secondCollections = $this->getUserBrand($secondIdsValid, $secondLimit);
            $secondCount = $secondCollections->count();
            $firstCollections = $firstCollections->merge($secondCollections);
            if ($secondCount < $secondLimit) {
                $thirdLimit = $secondLimit - $secondCount;
                $thirdCollections = $this->getUserBrand($thirdIdsValid, $thirdLimit);
                $firstCollections = $firstCollections->merge($thirdCollections);
            }
        }

        return $firstCollections;
    }

    /**
     * Get user brand
     *
     * @param $ids
     * @param $limit
     *
     * @return collection
     */
    private function getUserBrand($ids, $limit)
    {
        return $this->userRepository->getByIds($ids)
            ->orderBy('point', 'DESC')
            ->limit($limit)
            ->get();
    }

    private function getIdsByPriority($keyword, $priority)
    {
        $ids = $this->getPluckId($this->userRepository->getByName($keyword));
        if ($priority != SearchEnum::TYPE_PRIORITY_FIRST) {
            $secondIds = $this->getPluckId($this->userRepository->getBySearchLike([$keyword]));
            $ids = array_diff($secondIds, $ids);
            if ($priority == SearchEnum::TYPE_PRIORITY_THIRD) {
                $thirdIds = $this->getPluckId($this->userRepository->getBySearchLike(explode(' ', $keyword)));
                $ids = array_diff($thirdIds, $secondIds);
            }
        }

        return $ids;
    }

    private function getIdsByTopicName($keyword)
    {
        $topics = $this->topicRepository->getByHashTagName($keyword)->with('hashTagPosts')->get();
        $response = [];
        foreach ($topics as $topic) {
            $response = array_merge($response, $topic->hashTagPosts->pluck('post_id')->toArray());
        }

        return $response;
    }
}
