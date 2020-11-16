<?php

namespace App\Services;

use App\Repositories\Category\CategoryRepositoryInterface;

class CategoryService
{
    /**
     *
     * @var CategoryRepositoryInterface
     */
    private $categoryRepository;

    /**
     * CategoryService constructor.
     *
     * @param CategoryRepositoryInterface $categoryRepository
     */
    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function getCategoryWithTopic($hotest)
    {
        return $this->categoryRepository->getCategoryWithTopic($hotest);
    }

    /**
     * Show detail category with topic.
     *
     * @param string id
     *
     * @return model
     */
    public function showCategoryWithId($id)
    {
        return $this->categoryRepository->findOrFailWithRelation($id, 'hashTags', 'point', 'DESC');
    }

    /**
     *
     * Search Category.
     *
     * @param  string  $keyword
     *
     * @return Collection
     */
    public function search($keyword)
    {
        return $this->categoryRepository->search($keyword);
    }
}
