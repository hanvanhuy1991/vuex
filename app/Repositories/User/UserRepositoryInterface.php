<?php

namespace App\Repositories\User;

use App\Repositories\BaseRepositoryInterface;

/**
 * Interface UserRepositoryInterface
 *
 * @package App\Repositories\User
 */
interface UserRepositoryInterface extends BaseRepositoryInterface
{
    public function getUserByRole($role);

    public function getOrderByToPost();

    public function getProfile($user);

    public function getByName($keyword);

    public function getBySearchLike($keywords);

    public function getByIds($ids, $removeBlock = true);

    public function getBusinessAccountByIds($ids);

    public function suggestion($lat, $long, $validIds, $inValidIds, $phones);

    public function getProfileAdmin($userId);

    public function getAccountForSearch();

    /**
     * Get Profile Detail account for CMS
     *
     * @param request
     *
     * @return model
     */
    public function getProfileAccountCms($id);
}
