<?php

namespace App\Services;

use App\Repositories\Favorite\FavoriteRepositoryInterface;

class FavoriteService
{
    /**
     *
     * @var FavoriteRepositoryInterface
     */
    private $favoriteRepository;

    /**
     * FavoriteService constructor.
     *
     * @param FavoriteRepositoryInterface $favoriteService
     */
    public function __construct(FavoriteRepositoryInterface $favoriteRepository)
    {
        $this->favoriteRepository = $favoriteRepository;
    }

    public function getFavorite()
    {
        return $this->favoriteRepository->getFavorite();
    }
}
