<?php

namespace App\Repositories\Image;

use App\Models\Image;
use App\Repositories\BaseRepository;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class ImageRepository
 *
 * @package App\Repositories\Image
 */
class ImageRepository extends BaseRepository implements ImageRepositoryInterface
{
    /**
     * @var Image
     */
    protected $model;

    /**
     * @var DatabaseManager
     */
    private $db;

    /**
     * ImageRepository constructor.
     *
     * @param Image $model
     * @param DatabaseManager $db
     */
    public function __construct(Image $model, DatabaseManager $db)
    {
        parent::__construct($model);

        $this->db = $db;
    }

    /**
     * @param array $paths
     *
     * @return Builder
     */
    public function whereImagePath($paths)
    {
        return $this->model->whereIn('image_path', $paths);
    }

    /**
     * @param array $ids
     *
     * @return Builder
     */
    public function getByIds($ids)
    {
        return $this->model->whereIn('id', $ids);
    }
}
