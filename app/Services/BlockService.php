<?php

namespace App\Services;

use App\Enums\BlockEnum;
use Illuminate\Support\Facades\Auth;
use App\Repositories\User\UserRepositoryInterface;

class BlockService
{
    /**
     * @var UserRepositoryInterface
     */
    protected $userRepository;

    /**
     * BlockService constructor.
     *
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Get paginate user block
     *
     * @return builder
     */
    public function index()
    {
        $isBlockedIds = Auth::user()->blockers()
            ->whereStatus(BlockEnum::STATUS_BLOCK)
            ->pluck('is_blocked_id')
            ->toArray();

        return $this->userRepository->getByIds($isBlockedIds, false);
    }

    /**
     * Block / UnBlock user
     *
     * @param $user
     *
     * @return collection
     */
    public function store($user)
    {
        $query = Auth::user()->blockers();
        $status = (int)$query->whereIsBlockedId($user->id)->value('status') ?? BlockEnum::STATUS_UNBLOCK;
        $query->updateOrCreate([
            'is_blocked_id' => $user->id
        ], [
            'status' => !$status
        ]);

        return $user;
    }
}
