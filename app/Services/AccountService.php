<?php

namespace App\Services;

use App\Repositories\User\UserRepositoryInterface;

class AccountService
{
    /**
     *
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * UserService constructor.
     *
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(
        UserRepositoryInterface $userRepository
    ) {
        $this->userRepository = $userRepository;
    }

    /**
     * Get list account for CMS
     *
     * @param request
     *
     */
    public function listAccountCms($request)
    {
        return $this->userRepository->listAccountCms($request);
    }

    /**
     * Get Profile Detail account for CMS
     *
     * @param request
     *
     * @return model
     */
    public function getProfileAccount($id)
    {
        return $this->userRepository->getProfileAccountCms($id);
    }
}
