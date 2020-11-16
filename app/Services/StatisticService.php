<?php

namespace App\Services;

use App\Enums\FollowEnum;
use App\Enums\LikeEnum;
use App\Enums\PostEnum;
use App\Enums\StatisticEnum;
use App\Enums\UserRole;
use App\Repositories\Comment\CommentRepositoryInterface;
use App\Repositories\Like\LikeRepositoryInterface;
use App\Repositories\Share\ShareRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StatisticService
{
    /**
     * @var $userRepository
     * @var $commentRepository
     * @var $likeRepository
     * @var $shareRepository
     */
    protected $userRepository;
    protected $commentRepository;
    protected $likeRepository;
    protected $shareRepository;

    /**
     * StatisticService constructor.
     *
     * @param UserRepositoryInterface $userRepository
     * @param CommentRepositoryInterface $commentRepository
     * @param LikeRepositoryInterface $likeRepository
     * @param ShareRepositoryInterface $shareRepository
     */
    public function __construct(
        UserRepositoryInterface $userRepository,
        CommentRepositoryInterface $commentRepository,
        LikeRepositoryInterface $likeRepository,
        ShareRepositoryInterface $shareRepository
    ) {
        $this->userRepository = $userRepository;
        $this->commentRepository = $commentRepository;
        $this->likeRepository = $likeRepository;
        $this->shareRepository = $shareRepository;
    }

    /**
     * Get total follower
     *
     * @return mixed
     */
    public function getTotalFollower()
    {
        return $this->followers()->count();
    }

    /**
     * Get age range follower
     *
     * @param $gender
     *
     * @return array
     */
    public function getAgeFollower($gender)
    {
        $rangeAges = StatisticEnum::rangeAges();
        $sql = $this->getSqlCountRangeBirth($rangeAges);

        $ids = $this->followers()->pluck('follower_id')->toArray();
        $minBirthday = (date('Y') - StatisticEnum::AGE_MIN).'-01-01';

        $query = $this->userRepository->getByIds($ids)->select(DB::raw($sql))
            ->where('birthday', '<', $minBirthday)
            ->whereNotNull('birthday');

        if (isset($gender)) {
            $query = $query->whereGender($gender);
        }

        $users = $query->groupBy('range')->pluck('count', 'range')->toArray();

        $response = [];
        foreach ($rangeAges as $rangeAge) {
            $key = implode($rangeAge, '-');
            $response[] = [
                'range' => $key,
                'count' => $users[$key] ?? 0
            ];
        }
        $keyMax = StatisticEnum::AGE_MAX.'+';
        $response[] = [
            'key' => $keyMax,
            'count' => $users[$keyMax] ?? 0
        ];

        return $response;
    }

    /**
     * Get sql count range birth
     *
     * @param $rangeAges
     *
     * @return string
     */
    private function getSqlCountRangeBirth($rangeAges)
    {
        $sql = "CASE";
        $other = StatisticEnum::AGE_MAX;
        foreach ($rangeAges as $rangeAge) {
            $key = implode($rangeAge, '-');
            $min = date('Y') - $rangeAge['min'];
            $max = date('Y') - $rangeAge['max'];
            $default = '01-01';
            $sql .= " WHEN birthday between '$max-$default' and '$min-$default' then '$key'";
        }
        $sql .= " ELSE '$other+'";
        $sql .= ' END as `range`, count(1) as `count`';

        return $sql;
    }

    /**
     * Get sql count range gender
     *
     * @return mixed
     */
    public function getGenderFollower()
    {
        $ids = $this->followers()->pluck('follower_id')->toArray();
        $sql = $this->getSqlCountGender();

        return $this->userRepository->getByIds($ids)
            ->select(DB::raw($sql))
            ->whereNotNul('gender')
            ->groupBy('gender')
            ->get();
    }

    /**
     * Get sql count range gender
     *
     * @return string
     */
    private function getSqlCountGender()
    {
        $sql = "CASE";
        foreach (UserRole::genders() as $key => $gender) {
            $sql .= " WHEN gender = $gender  then '$key'";
        }
        $sql .= " ELSE 'other'";
        $sql .= ' END as `gender`, count(1) as `count`';

        return $sql;
    }

    /**
     * Get follower chart
     *
     * @param $time
     *
     * @return array
     */
    public function getFollowerChart($time)
    {
        $sql = $this->getSqlCountFollowInDay($time);

        $followers = $this->followers()
            ->select(DB::raw($sql))
            ->groupBy('date')
            ->pluck('count', 'date')
            ->toArray();

        $response = [];
        $now = date('Y-m-d');
        $start = $time == StatisticEnum::TODAY ? 0 : 1;
        for ($i = $start; $i <= $time; $i++) {
            $date = date('Y-m-d', strtotime("-$i day", strtotime($now)));
            $response[] = [
                'date' => $date,
                'count' => $followers[$date] ?? 0,
                'name' => date('D', strtotime($date))
            ];
        }

        return array_reverse($response);
    }

    /**
     * Get sql count follower in day
     *
     * @param $time
     *
     * @return string
     */
    private function getSqlCountFollowInDay($time)
    {
        $sql = "CASE";
        $now = date('Y-m-d');

        if ($time != StatisticEnum::TODAY) {
            for ($i = 1; $i <= $time; $i++) {
                $date = date('Y-m-d', strtotime("-$i day", strtotime($now)));
                $sql .= " WHEN updated_at like '%$date%'  then '$date'";
            }
        } else {
            $sql .= " WHEN updated_at like '%$now%'  then '$now'";
        }

        $sql .= " ELSE 'other'";
        $sql .= ' END as `date`, count(1) as `count`';

        return $sql;
    }

    /**
     * Builder follower
     *
     * @return builder
     */
    private function followers()
    {
        return Auth::user()->followers()->has('follower')->whereStatus(FollowEnum::STATUS_ACTIVE);
    }

    public function engagement($time)
    {
        $now = date('Y-m-d');
        $date = date('Y-m-d', strtotime("-$time day", strtotime($now)));
        $posts = Auth::user()->posts()
            ->select('id', 'slug', 'thumbnail', 'view')
            ->where('action_time', '>=', $date)
            ->whereStatus(PostEnum::STATUS_ACTIVE);

        $postsIds = $posts->pluck('id')->toArray();
        $commentCount = $this->commentRepository->getByPostIds($postsIds)->count();
        $likeCount = $this->likeRepository->getByPostIds($postsIds)->count();
        $shareCount = $this->shareRepository->getByPostIds($postsIds)->count();
        $viewCount = array_sum($posts->pluck('view')->toArray());
        $data = $posts->withCount(['shares', 'comments'])
            ->withCount(['likes' => function ($q) {
                $q->whereIsLiked(LikeEnum::STATUS_LIKE);
            }])
            ->orderBy('action_time', 'DESC');

        return [
            'total' => $commentCount + $likeCount + $shareCount + $viewCount,
            'data' => $data
        ];
    }
}
