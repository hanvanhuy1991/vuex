<?php

namespace App\Repositories\Reason;

use App\Models\LabelReason;
use App\Repositories\BaseRepository;
use Illuminate\Database\DatabaseManager;

class LabelReasonRepository extends BaseRepository implements LabelReasonRepositoryInterface
{
    private $db;
    protected $model;

    public function __construct(
        LabelReason $model,
        DatabaseManager $db
    ) {
        parent::__construct($model);

        $this->db = $db;
    }

    public function getByType($type)
    {
        return $this->model->whereType($type)->with('reasons')->get();
    }
}
