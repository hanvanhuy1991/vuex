<?php

namespace App\Services;

use App\Repositories\Page\PageRepositoryInterface;

class PageService
{
    /**
     *
     * @var PageRepositoryInterface
     */
    private $pageRepository;

    /**
     * PageService constructor.
     *
     * @param PageRepositoryInterface $pageRepository
     */
    public function __construct(PageRepositoryInterface $pageRepository)
    {
        $this->pageRepository = $pageRepository;
    }

    public function getPage($type)
    {
        return $this->pageRepository->firstOrFail('type', $type);
    }
}
