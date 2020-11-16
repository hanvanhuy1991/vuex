<?php

namespace App\Repositories\Interest;

use App\Models\Interest;
use App\Repositories\BaseRepository;
use Illuminate\Database\DatabaseManager;

class InterestRepository extends BaseRepository implements InterestRepositoryInterface
{
    /**
     * @var Interest
     */
    protected $model;

    /**
     * @var DatabaseManager
     */
    private $db;

    /**
     * FollowRepository constructor.
     *
     * @param Interest $model
     * @param DatabaseManager $db
     */
    public function __construct(
        Interest $model,
        DatabaseManager $db
    ) {
        parent::__construct($model);

        $this->db = $db;
    }

    public function getByInterestedInIds($ids)
    {
        return $this->model->whereIn('interested_in', $ids);
    }
}
