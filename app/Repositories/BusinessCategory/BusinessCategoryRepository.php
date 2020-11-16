<?php

namespace App\Repositories\BusinessCategory;

use App\Models\BusinessCategory;
use App\Enums\BusinessCategoryEnum;
use App\Repositories\BaseRepository;
use Illuminate\Database\DatabaseManager;
use App\Repositories\BusinessCategory\BusinessCategoryRepositoryInterface;

/**
 * Class BusinessCategory
 *
 * @package App\Repositories\BusinessCategory
 */
class BusinessCategoryRepository extends BaseRepository implements BusinessCategoryRepositoryInterface
{
    /**
     * @var Topic
     */
    protected $model;

    /**
     * @var DatabaseManager
     */
    private $db;

    /**
     * BusinessCategoryRepository constructor.
     *
     * @param BusinessCategory $model
     * @param DatabaseManager $db
     */
    public function __construct(BusinessCategory $model, DatabaseManager $db)
    {
        parent::__construct($model);

        $this->db = $db;
    }

    /**
     *
     * List business category no paginate
     *
     * @return $collection
     */
    public function getListBusinessCategory()
    {
        return $this->model->whereStatus(BusinessCategoryEnum::STATUS_ACTIVE)->orderBy('created_at', 'DESC')->get();
    }
}
