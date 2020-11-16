<?php

namespace App\Repositories\Collection;

use App\Models\Collection;
use App\Enums\CollectionEnum;
use Illuminate\Support\Facades\Auth;
use App\Repositories\BaseRepository;
use Illuminate\Database\DatabaseManager;

class CollectionRepository extends BaseRepository implements CollectionRepositoryInterface
{
    /**
     * @var Collection
     */
    protected $model;

    /**
     * @var DatabaseManager
     */
    private $db;

    /**
     * CommentRepository constructor.
     * @param Collection $model
     * @param DatabaseManager $db
     */
    public function __construct(
        Collection $model,
        DatabaseManager $db
    ) {
        parent::__construct($model);

        $this->db = $db;
    }

    /**
     * Get builder collection by auth user and type bookmark
     *
     * @return builder
     */
    public function index()
    {
        $conditions = [
            'user_id' => Auth::id(),
            'type' => CollectionEnum::BOOKMARK
        ];
        $query = $this->model->where($conditions);

        if (!$query->exists()) {
            $inserts = array_merge($conditions, ['name' => CollectionEnum::NAME_DEFAULT]);
            $query->create($inserts);
        }

        return $query->with('posts');
    }
}
