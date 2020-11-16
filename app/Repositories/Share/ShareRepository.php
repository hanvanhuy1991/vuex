<?php

namespace App\Repositories\Share;

use App\Models\Share;
use App\Repositories\BaseRepository;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Eloquent\Model;

class ShareRepository extends BaseRepository implements ShareRepositoryInterface
{
    /**
     * @var Share
     */
    protected $model;

    /**
     * @var DatabaseManager
     */
    private $db;

    /**
     * ShareRepository constructor.
     *
     * @param Share $model
     * @param DatabaseManager $db
     */
    public function __construct(
        Share $model,
        DatabaseManager $db
    ) {
        parent::__construct($model);

        $this->db = $db;
    }

    /**
     * Get by post ids
     *
     * @param $ids
     *
     * @return mixed
     */
    public function getByPostIds($ids)
    {
        return $this->model->whereIn('post_id', $ids);
    }
}
