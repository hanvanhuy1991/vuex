<?php

namespace App\Http\Controllers;

use App\Enums\StatisticEnum;
use App\Enums\UserRole;
use App\Http\Resources\EngagementResource;
use App\Services\StatisticService;
use Illuminate\Http\Request;

class StatisticController extends BaseController
{
    /**
     * @var $statisticService
     */
    protected $statisticService;

    /**
     * StatisticController constructor.
     *
     * @param StatisticService $statisticService
     */
    public function __construct(StatisticService $statisticService)
    {
        parent::__construct();

        $this->statisticService = $statisticService;
    }

    /**
     * Get total follower
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTotalFollower()
    {
        return $this->responseSuccess([
            'total' => $this->statisticService->getTotalFollower()
        ]);
    }

    /**
     * Get age range follower
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAgeFollower(Request $request)
    {
        $gender = in_array($request->gender, UserRole::genders()) ? $request->gender : null;

        return $this->responseSuccess($this->statisticService->getAgeFollower($gender));
    }

    /**
     * Get age range follower
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getGenderFollower()
    {
        return $this->responseSuccess($this->statisticService->getGenderFollower());
    }

    /**
     * Get follower chart
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFollowerChart(Request $request)
    {
        $timePeriod = $request->time_period;
        $time = in_array($timePeriod, StatisticEnum::timePeriods()) && isset($timePeriod) ? $request->time_period : StatisticEnum::WEEKLY;

        return $this->responseSuccess($this->statisticService->getFollowerChart((int)$time));
    }

    public function engagement(Request $request)
    {
        $timePeriod = $request->time_period;
        $time = in_array($timePeriod, StatisticEnum::timePeriods()) && isset($timePeriod) ? $timePeriod : StatisticEnum::WEEKLY;
        $response = $this->statisticService->engagement((int)$time);

        return $this->responseSuccess([
            'total' => $response['total'],
            'data' => EngagementResource::apiPaginate($response['data'], $request)
        ]);
    }
}
