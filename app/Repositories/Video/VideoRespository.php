<?php

namespace App\Repositories\Video;

use App\Models\Video;
use App\Repositories\BaseRepository;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class VideoRepository
 *
 * @package App\Repositories\Video
 */
class VideoRepository extends BaseRepository implements VideoRepositoryInterface
{
    /**
     * @var Video
     */
    protected $model;

    /**
     * @var DatabaseManager
     */
    private $db;

    /**
     * VideoRepository constructor.
     *
     * @param Video $model
     * @param DatabaseManager $db
     */
    public function __construct(Video $model, DatabaseManager $db)
    {
        parent::__construct($model);

        $this->db = $db;
    }

    /**
     * @param array $paths
     *
     * @return Builder
     */
    public function whereVideoPath($paths)
    {
        return $this->model->whereIn('video_path', $paths);
    }
}
