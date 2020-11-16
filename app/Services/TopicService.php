<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Repositories\Topic\TopicRepositoryInterface;
use App\Repositories\Category\CategoryRepositoryInterface;

class TopicService
{
    /**
     *
     * @var TopicRepositoryInterface
     */
    private $topicRepository;

    /**
     *
     * @var CategoryRepositoryInterface
     */
    private $categoryRepository;

    /**
     * TopicRepositoryInterface constructor.
     *
     * @param TopicRepositoryInterface $topicRepository
     */
    public function __construct(TopicRepositoryInterface $topicRepository, CategoryRepositoryInterface $categoryRepository)
    {
        $this->topicRepository = $topicRepository;
        $this->categoryRepository = $categoryRepository;
    }


    public function hotRanking()
    {
        return $this->topicRepository->hotRanking();
    }
    /**
     * Store Topic with Category
     *
     * @param $request
     *
     * @return $model
     */
    public function store($request)
    {
        DB::beginTransaction();
        try {
            $category_id = $request['category_id'];
            $topic_name =  $request['topic_name'];

            $topic = $this->topicRepository->checkTopicAndCategoryExist($request);
            $category = $this->categoryRepository->findById($category_id);

            if ($topic->isEmpty() && $category) {
                $newTopic =  $this->topicRepository->create([
                    'hash_tag_name' => $request['topic_name'],
                    'topic_id' => $request['category_id']
                ]);
                $category_id  = $newTopic->topic_id;
                $topic_name  = $newTopic->hash_tag_name;
            }

            DB::commit();

            return $this->topicRepository->getTopicAndCategoryById($request['category_id'], $request['topic_name']);
        } catch (Exception $ex) {
            DB::rollback();

            report($e);
        }
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
        return $this->topicRepository->search($keyword);
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
        return $this->topicRepository->searchTopicWithCategory($keyword, $categoryId);
    }
}
