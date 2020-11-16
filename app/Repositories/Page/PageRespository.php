<?php

namespace App\Repositories\Page;

use App\Models\Page;
use App\Repositories\BaseRepository;
use Illuminate\Database\DatabaseManager;

/**
 * Class PageRepository
 *
 * @package App\Repositories\Page
 */
class PageRepository extends BaseRepository implements PageRepositoryInterface
{
    /**
     * @var Page
     */
    protected $model;

    /**
     * @var DatabaseManager
     */
    private $db;

    /**
     * PageRepository constructor.
     *
     * @param Page $model
     * @param DatabaseManager $db
     */
    public function __construct(Page $model, DatabaseManager $db)
    {
        parent::__construct($model);

        $this->db = $db;
    }
}
