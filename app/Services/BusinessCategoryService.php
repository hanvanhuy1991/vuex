<?php

namespace App\Services;

use App\Repositories\BusinessCategory\BusinessCategoryRepositoryInterface;

class BusinessCategoryService
{
    /**
     *
     * @var businessCategoryRepositoryInterface
     *
     */
    private $businessCategoryRepository;

    /**
     * BusinessCategoryService constructor.
     *
     * @param BusinessCategoryRepositoryInterface $businessCategoryRepository
     */
    public function __construct(
        BusinessCategoryRepositoryInterface $businessCategoryRepository
    ) {
        $this->businessCategoryRepository = $businessCategoryRepository;
    }

    /**
     * Get list account for CMS
     *
     * @param request
     *
     */
    public function getListBusinessCategory()
    {
        return $this->businessCategoryRepository->getListBusinessCategory();
    }
}
